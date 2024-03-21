<?php

session_start();

    include 'conexion.php';

    
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $hashed_correo_query= "SELECT CRE_CPASSWORD FROM TBL_RCREDENCIAL WHERE CRE_CCORREO=?";
    $params= array($correo);
    $result = sqlsrv_query($con, $hashed_correo_query, $params);

if($result && sqlsrv_num_rows($result)){
  $row = sqlsrv_fetch_array($result);
  $hashed_password = $row['CRE_CPASSWORD'];

  if (password_verify($password, $hashed_password)) {
    // Contraseña válida
    $_SESSION['CRE_CCORREO'] = $correo;
    header("location: ../php_paginas/bienvenida.php");
    exit();
  }
}

    if (!$result) {
      die(print_r(sqlsrv_errors(), true));
  }
    echo '
        <script>
             alert("Error, the user not exist");
             window.location= "../index.php";
          </script>
          ';
          exit();
  

  
?>