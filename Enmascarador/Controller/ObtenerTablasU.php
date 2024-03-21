<?php
require '../conexion/setting.php';
if (isset($_POST['bds'])) {
    $selectedBD = $_POST['bds'];
    
    if ($selectedBD!="" && $selectedBD!=null){

        $nParamsConn = array("Database" => $selectedBD, "UID" => USER, "PWD" => PASSWOR, "CharacterSet" => "UTF-8", "language"=>"spanish");
        sqlsrv_configure('WarningsReturnAsErrors',0);
        $nConn = sqlsrv_connect(SERVER,$nParamsConn);
        $queryTables = "SELECT * from sys.sysobjects where name like '%TBL_U%' and xtype ='U'";
        //$params = array($selectedBD);
        $resultTables = sqlsrv_query($nConn, $queryTables);

        if ($resultTables) {
            $json = "";
            while ($row = sqlsrv_fetch_array($resultTables, SQLSRV_FETCH_ASSOC)) {
                $json .=  ($json=="" ? "" : ","). ''. $row['name'] . '';
            }
            header('Content-Type: application/json');
            echo $json;
            return;
        } else {
            die(print_r(sqlsrv_errors(), true));
        }
        
    }
}
?>