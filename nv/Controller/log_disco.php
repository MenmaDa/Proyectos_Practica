<?php

function log_a_disco ($valor)
{
    //open the file 
    $square_file = fopen("c:\logphp.txt", "a+");
    //write the log.
    $line = "$valor .\n";
    fwrite($square_file, $line);

    //read the first line of the file and echo 
    //fseek($square_file, 0);
    //echo fgets($square_file);
    //close the file 
    fclose($square_file);
    
}

function verificar_solicitud()
{
    try{
        if (file_exists("c:\intercambiador_enmascarador.txt")){
            //$intercambiador = fopen("c:\intercambiador_enmascarador.txt","r");
            //$parametros = fread($intercambiador,filesize("c:\intercambiador_enmascarador.txt"));
            //fclose($intercambiador);
            $intercambiador = file("c:\intercambiador_enmascarador.txt");
            $valid = true;
            foreach($intercambiador as $line){
                $splited_params = explode(";",$line);
                $valid = $valid && (count($splited_params) > 0 ? $splited_params[0] == "0" : true);
            }
            return $valid;
        }else{
            return true;
        }
    }catch(Exception $e){
        return false;
    }
}

function registrar_solicitud($valor)
{
    try{
        $intercambiador = fopen("c:\intercambiador_enmascarador.txt","w");
        file_put_contents("c:\intercambiador_enmascarador.txt",$valor);
    }catch(Exception $e){

    }    
}

?>