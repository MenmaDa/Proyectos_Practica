<?php

session_start();

if(!isset($_SESSION['CRE_CUSUARIO'])){
    echo '
    <script>
        alert("please, log in");
        window.location= "../index.php";
    </script>
    ';
    session_destroy();
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=h1, initial-scale=1.0">
    <title>Bienvenida</title>
</head>
<body>
    <h1>Bienvenido a el enmarcarador de informacion</h1>
    <label for=""><a href="php_datos_db/cerrar_sesion.php">Cerrar Sesión</a></label>
    <main>
        <div class="Contenedor">
            
        <select>
      <option value="HTML">Select a Language</option>
      <option value="HTML">HTML</option>
      <option value="CSS">CSS</option>
      <option value="JavaScript">JavaScript</option>
      <option value="React">React</option>
</select>

        </div>
    </main>
</body>
</html>