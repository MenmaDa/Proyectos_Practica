<?php

require 'setting.php';

    $connectionInfo = array("Database" => DATABASE, "UID" => USER, "PWD" => PASSWORD, "CharacterSet" => "UTF-8", "language"=>"spanish");
    sqlsrv_configure('WarningsReturnAsErrors',0);
    $con = sqlsrv_connect(SERVER, $connectionInfo);

    if ($con === false) {
        die(print_r(sqlsrv_errors(), true));
    }

//  if($con){
//  echo "Connection Successfully";
//  }else{
//     echo "Error connecting to database";
// }

?>