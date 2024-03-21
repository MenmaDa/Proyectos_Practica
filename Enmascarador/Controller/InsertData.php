<?php
try{
   require '../conexion/setting.php'; //este script define los parameros de conexion a la BD de la master
   include '../Controller/log_disco.php'; //este script controla el log a disco

  $Object = new DateTime();  
 $DateAndTime = $Object->format("d-m-Y h:i:s a");  

if (isset($_POST['InsertColumns'], $_POST['tbls'], $_POST['bds'], $_POST['TextInsert'])) {

         $selectedCL = $_POST['InsertColumns'];
         $selectedTB =  $_POST['tbls'];
         $selectDB = $_POST['bds'];
         $textInput = $_POST['TextInsert'];
            
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
            
    //Log a disco para pruebas de escritorio de la funcion
    log_a_disco ( $DateAndTime  ." -->Database:" . $selectDB);
    log_a_disco ( $DateAndTime ." -->Tabla:" .$selectedTB);
    log_a_disco ( $DateAndTime ." -->Columnas:" .json_encode($selectedCL));
    log_a_disco ( $DateAndTime ." -->Conexion DB dinamica: SERVER" .SERVER ." -->Usuario DB: " .USER ." -->Clave DB: " .PASSWORD);

    $spName = "SPR_CHARGE_DATOS";

    //Recorre las columnas 
    $columnas= '';

     foreach($selectedCL as $col){
     $columnas .= ($columnas == '' ? '' : ',').$col;
     }
     $PI_COLUMNAS = $columnas;
    $Parametro = '@PI_COLUMNAS = '. "'" . $PI_COLUMNAS . "'";
    echo $Parametro;

    $columnasText= '';
    foreach($textInput as $coltext){
        $columnasText .= ($columnasText == '' ? '' : ',').$coltext;
    }
    $PI_TEXTO = $columnasText;
    $Parametro_Texto = '@PI_TEXTO = '. "'" . $PI_TEXTO . "'";
    echo $Parametro_Texto;


    $sql ="EXEC $spName @PI_NOMBRE_TABLA = $selectedTB, $Parametro, $Parametro_Texto";

    $stmt = sqlsrv_prepare($nConnection, $sql);
            
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
    }else{
    echo 'No se encontro el sp';
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
