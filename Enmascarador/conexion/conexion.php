<?php

require 'setting.php';

$connection_info = array("Database" => DATABASE, "UID" => USER, "PWD" => PASSWORD, "CharacterSet" => "UTF-8", "language"=>"spanish");
sqlsrv_configure('WarningsReturnAsErrors',0);
$conexion = sqlsrv_connect(SERVER,$connection_info);

if ($conexion === false) {
    die(print_r(sqlsrv_errors(), true));
}
/*
if ($conexion){
 echo'Conexión exitosa';
}else{
    echo'Conexión fallida';
}
*/
?>
