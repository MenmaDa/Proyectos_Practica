<?php
include '../conexion/conexion.php';

$query = "SELECT * FROM sys.databases where name not in ('master','tempdb','model','msdb')";
$result = sqlsrv_query($conexion, $query);

if ($result) {
    $dataColl = "";
    $json = "";
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        //echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
        $json .=  ($json==" " ? "" : ","). ''. $row['name'] . '';
    }
    header('Content-Type: application/json');
    echo $json;
    return;
} else {
    die(print_r(sqlsrv_errors(), true));
}



?>