<?php
    require '../conexion/setting.php';
    header('Content-Type: application/json');
    
    try{
        if (isset($_POST['bds'])) {
            $selectedBD = $_POST['bds'];
            
            if ($selectedBD!="" && $selectedBD!=null){
        
                $nParamsConn = array("Database" => $selectedBD, "UID" => USER, "PWD" => PASSWORD, "CharacterSet" => "UTF-8", "language"=>"spanish");
                sqlsrv_configure('WarningsReturnAsErrors',0);
                $nConn = sqlsrv_connect(SERVER,$nParamsConn);
                $resultQuery = sqlsrv_query($nConn, "SELECT ISNULL(TEE_OESTADO,0) FROM TBL_TESTADO_EJECUCION");   
                
                if ($resultQuery){
                    $canExecute = false;
                    while ($statusRs = sqlsrv_fetch_array($resultQuery, SQLSRV_FETCH_ASSOC)) {
                        $canExecute = $statusRs['TEE_OESTADO'] != 1;
                    }
                    echo($canExecute ? "1" : "0");           
                }else{
                    //indica que la consulta de errores retorno datos
                    echo('{Error:"Ocurrió un error en la base de datos",desc:""}');
                }
            }
        }else{
            echo('{Error:"No se envío la base de datos",desc:"El proceso no se puede ejecutar sin una base de datos de origen"}');
        }
    }catch(Exception $e){
        echo('{Error:"Se produjo una excepción durante el proceso",desc:"'. $e->getMessage() .'"}');
    }
?>