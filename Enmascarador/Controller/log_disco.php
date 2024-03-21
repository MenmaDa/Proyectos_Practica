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

?>