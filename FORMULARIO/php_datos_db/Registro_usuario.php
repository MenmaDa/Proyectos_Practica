<?php

    include 'conexion.php';

    $nombre_completo = $_POST['nombre_completo'];
    $correo = strtolower(trim($_POST['correo']));
    $usuario = strtolower(trim($_POST['usuario']));
    $password = $_POST['password'];
    //encripción de usuario y contraseña
    //fin de la encripción
    $query= "INSERT INTO TBL_RCREDENCIAL(CRE_CNOMBRE_COMPLETO, CRE_CCORREO, CRE_CUSUARIO, CRE_CPASSWORD) 
            VALUES($nombre_completo, $correo, $usuario, $password)";
       
    
    $verificar_correo_query = "SELECT * FROM TBL_RCREDENCIAL WHERE CRE_CCORREO = $correo ";

    //verificacion que el correo y usuario no se repitan en la base de datos
    $verificar_correo= sqlsrv_query($con, $verificar_correo_query);

    if(sqlsrv_num_rows($verificar_correo) > 0){
      echo '
      <script>
           alert("Error, the email is already registered");
           window.location= "../index.php";
        </script>
        ';
        exit();
    }

    $verificar_usuario_query = "SELECT * FROM TBL_RCREDENCIAL WHERE CRE_CUSUARIO = $usuario ";
    echo $verificar_usuario_query;
    $verificar_usuario = sqlsrv_query($con, $verificar_usuario_query);

    if(sqlsrv_num_rows($verificar_usuario) > 0){
        echo '
        <script>
             alert("Error, the user is already registered");
             window.location= "../index.php";
          </script>
          ';
          exit();
      }
    //fin de la verificacion

    $ejecutar = sqlsrv_query($con, $query, $params); 

    if($ejecutar){
        echo '
        <script>
           alert("Successful Registration");
           window.location= "../index.php";
        </script>
        ';
    }else{
        echo '
        <script>
           alert("Try Again, Unsuccessful registration");
           window.location= "../index.php";
        </script>
        ';
    }
    sqlsrv_close($con);
?>