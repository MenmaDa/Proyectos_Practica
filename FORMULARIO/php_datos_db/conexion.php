<?php
require 'setting.php';

    $connectionInfo = array("Database" => DATABASE, "UID" => USER, "PWD" => PASSWORD);


$con = sqlsrv_connect(SERVER,$connectionInfo);

//mirar si esta conectada la base de datos

if( $con === false) {
    die( print_r( sqlsrv_errors(), true));
    echo 'error al conectarse';
}

// if($con){
// echo "Connection Successfully";
// }else{
//     echo "Error connecting to database";
// }

?>