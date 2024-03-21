<?php
try{
    require '../conexion/setting.php'; //este script define los parameros de conexion a la BD de la master
    include '../Controller/log_disco.php'; //este script controla el log a disco

    $Object = new DateTime();  
    $DateAndTime = $Object->format("d-m-Y h:i:s a");  

        if (isset($_POST['CheckColumns'], $_POST['tbls'], $_POST['bds'])) {

            $selectedCL = $_POST['CheckColumns'];
            $selectedTB =  $_POST['tbls'];
            $selectDB = $_POST['bds'];
    
            
            if ($selectedCL!="" && $selectedCL!=null){


            //Conexion dinamica a la base de datos seleccionada.
            $nParamConn = array("Database" => $selectDB, "UID" => USER, "PWD" => PASSWORD, "CharacterSet" => "UTF-8", "language"=>"spanish");
            sqlsrv_configure('WarningsReturnAsErrors',0);
            $nConnection = sqlsrv_connect(SERVER,$nParamConn);
            //Revisa si hay conexion exitosa.
            if( $nConnection === false) {
                die( print_r( sqlsrv_errors(), true));
                echo 'error al conectarse';
            }

            // if (!isset($selectedCL)) {
            //     $selectedCL = array(); // o proporciona un valor predeterminado según tus necesidades
            //     echo  "Array vacio" + $selectedCL;
            // }
            
            //Log a disco para pruebas de escritorio de la funcion
            log_a_disco ( $DateAndTime  ." -->Database:" . $selectDB);
            log_a_disco ( $DateAndTime ." -->Tabla:" .$selectedTB);
            log_a_disco ( $DateAndTime ." -->Columnas:" .json_encode($selectedCL));
            log_a_disco ( $DateAndTime ." -->Conexion DB dinamica: SERVER" .SERVER ." -->Usuario DB: " .USER ." -->Clave DB: " .PASSWORD);


// //  if($query_crear_sp){
            $spName = "SPR_Prueba_ENMASCARAR_DATOS_PHP";

            //$params = array(&$selectedTB, implode(", ", $selectedCL));
            // $params = array(
            //     array(&$selectedCL, SQLSRV_PARAM_IN)
            // );
            
            
            // var_dump($selectedCL);
            // echo 'Tablas'.$selectedTB;

            $columnas= '';
            //  for($i = 0; $i < count($selectedCL); ++$i) {
            // $columnas .= ($columnas == '' ? '' : ',').$selectedCL[i];
            //  }
             foreach($selectedCL as $col){
                $columnas .= ($columnas == '' ? '' : ',').$col;
            }
            $PI_COLUMNAS = $columnas;
            //echo '@PI_COLUMNAS = '. "'" . $PI_COLUMNAS . "'";
            $Parametro = '@PI_COLUMNAS = '. "'" . $PI_COLUMNAS . "'";
            echo $Parametro;
            // Eliminar espacios después de las comas en $params[1]
            // $params[1] = preg_replace('/\s*,\s*/', ',', $params[1]);
            // Eliminar espacios alrededor de cada elemento en $params[1]
            // Mostrar el resultado
            // print_r($params[1]);
            // $ParamsUnifed = $params[1];

            $sql ="EXEC $spName @PI_NOMBRE_TABLA = $selectedTB, $Parametro";

            // $sql ="EXEC $spName $selectedTB, $params[1]";
            // echo "Sentencia SQL: $sql";
            
            $stmt = sqlsrv_prepare($nConnection, $sql);
            
            // $parameters = array(&$selectedTB, &$ParamsUnifed);
            // print_r($parameters);

            // echo "PI_NOMBRE_TABLA: $selectedTB<br>";
            // echo "PI_COLUMNAS: " . implode(", ", $selectedCL) . "<br>";
        
        // Verificar la preparación del statement
        if (!$stmt) {
            throw new Exception('Error al preparar el procedimiento almacenado.');
        }else{
            echo 'Procedimiento almacenado preparado correctamente.';
        }

        // Ejecutar el procedimiento almacenado
        if (sqlsrv_execute($stmt)) {
        echo 'Procedimiento almacenado ejecutado correctamente.';
        } else {
            throw new Exception('Error al ejecutar el procedimiento almacenado.');
        }
    
        // Cierra la conexión después de su uso
        sqlsrv_close($nConnection);
        
    //  }else{
    //      echo 'No se encontro el sp';
    //  }
    }
 }
 else {
    echo "Datos incompletos o no proporcionados.";
 }
}catch(Exception $e){
    echo 'Excepción capturada: ' . $e->getMessage();
    $sqlsrvErrors = sqlsrv_errors();
    if ($sqlsrvErrors !== null) {
        foreach ($sqlsrvErrors as $error) {
            echo 'SQLSTATE: ' . $error['SQLSTATE'] . ', Código: ' . $error['code'] . ', Mensaje: ' . $error['message'];
        }
    }
}
?>