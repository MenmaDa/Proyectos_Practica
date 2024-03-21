<?php
    require '../conexion/setting.php';
        if (isset($_POST['Cols'])) {
            $selectedTB = $_POST['Cols'];

            if ($selectedTB!="" && $selectedTB!=null){

            $nParamConn = array("Database" => $_POST['bds'], "UID" => USER, "PWD" => PASSWORD, "CharacterSet" => "UTF-8", "language"=>"spanish");
            sqlsrv_configure('WarningsReturnAsErrors',0);
            $nConnection = sqlsrv_connect(SERVER,$nParamConn);

            $queryColumns = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'" . $selectedTB . "'";
            
            //$params = array($selectedTB);
            $resultColumns = sqlsrv_query($nConnection, $queryColumns);
            $json = "";
            if ($resultColumns) {
                while ($rowColumns = sqlsrv_fetch_array($resultColumns, SQLSRV_FETCH_ASSOC)) {
                    $json .=  ($json=="" ? "" : ","). ''. $rowColumns['COLUMN_NAME'] . '';
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