<?php
try{
    require '../conexion/setting.php'; //este script define los parameros de conexion a la BD de la master
    include '../Controller/log_disco.php'; //este script controla el log a disco

    $Object = new DateTime();  
    $DateAndTime = $Object->format("d-m-Y h:i:s a");  
    ini_set('max_execution_time', 0);
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

            $queryStatus = "SELECT TEE_OESTADO FROM TBL_TESTADO_EJECUCION";
            $resultStatus = sqlsrv_query($nConnection, $queryStatus);
            $canExecute = false;
            if ($resultStatus) {
                while ($statusRs = sqlsrv_fetch_array($resultStatus, SQLSRV_FETCH_ASSOC)) {
                    $canExecute = $statusRs['TEE_OESTADO'] != 1;
                }
            }

            if ($canExecute){
                /**ob_start();
                echo ("EXECUTING");
                $size= ob_get_length();
                header("content-Encoding: none");
                header("Content-Length: {$size}");
                header("Connection : close");
                ob_end_flush();
                @ob_flush();
                flush();**/
                // if (!isset($selectedCL)) {
                //     $selectedCL = array(); // o proporciona un valor predeterminado según tus necesidades
                //     echo  "Array vacio" + $selectedCL;
                // }
                
                //Log a disco para pruebas de escritorio de la funcion
                log_a_disco ( $DateAndTime  ." -->Database:" . $selectDB);
                log_a_disco ( $DateAndTime ." -->Tabla:" .$selectedTB);
                log_a_disco ( $DateAndTime ." -->Columnas:" .json_encode($selectedCL));
                log_a_disco ( $DateAndTime ." -->Conexion DB dinamica: SERVER" .SERVER ." -->Usuario DB: " .USER ." -->Clave DB: " .PASSWORD);

                try{
                    // //  if($query_crear_sp){
                    $spName = "SPR_UDP_ENMASCARARDATOS";

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
                    $Parametro = '@PI_COLUMNAS = '. "'" . $PI_COLUMNAS . "'";
                    

                    //$sql ="EXEC $spName @PI_NOMBRE_TABLA = $selectedTB, $Parametro";

                    if (verificar_solicitud() || true){
                        $netConex = "Data Source=".SERVER.";Initial Catalog=".$selectDB.";User ID=".USER.";password=".PASSWORD;
                        registrar_solicitud("1|".$netConex."|".$selectedTB."|".$PI_COLUMNAS);
                        echo ("EXECUTING");
                    }else{
                        echo('NOEXECUTING');
                    }
                    
                }catch(Exception $e){
                    echo($e->getMessage());
                }

            }else{
                echo('NOEXECUTING');
            }
        }
    } else {
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