<?php

session_start();

    include 'conexion.php';
    echo "hola";
    
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $QUERY_VERIFICACION= "SELECT * FROM TBL_RCREDENCIAL WHERE CRE_CCORREO = '$correo' AND CRE_CPASSWORD = '$password'";
    $result = sqlsrv_query($con, $QUERY_VERIFICACION);

if($result){
  $existente = 0;
  while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    //echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
    $existente += 1;
}
  if($existente > 0){
    $_SESSION['usuario'] = $correo;
    header("Location: ../../APP/index.php");
  exit;
  }
  else{
    echo '
    <script>
         alert("Error, the email or password is not correct");
         window.location= "../Login.php";
      </script>
      ';
      exit;
  }
}else{
  echo '
        <script>
             alert("Error, login failed");
             window.location= "../Login.php";
          </script>
          ';
  exit;
}
sqlsrv_close($con);
?>