<?php
  session_start();

  if(isset($_SESSION['usuario'])){
    header("location: php_paginas/bienvenida.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/estilos.css">
    <title>Login y Regitro</title>
</head>
<body>
    
    <main>
        <!--Contenedor de todo-->
        <div class="contenedor_Todo">
        <!--Caja-->
            <div class="cajas">
                <!--Caja Izquierda-->
                <div class="caja__Izquierda-Login">
                    <h3>¿Ya tienes cuenta?</h3>
                    <p>Incia sesión para entrar en la página</p>
                    <button id="btn_iniciar-sesion">Iniciar Sesión</button>
                </div>
                <!--CajaDerecha-->
                <div class="caja__Derecha-Registro">
                    <h3>¿Aun no tienes una cuenta?</h3>
                    <p>Registrate para que puedas inciar sesión</p>
                    <button id="btn_registrarse">Registrate</button>
                </div>
            </div>
            <!--Contenedor de los formularios de registro y login-->
            <div class="contenedor__login-registro">
               <!--Formulario de Login-->
              <form action="php_datos_db/login_usuario.php" method="POST" class="formulario__login">
                <h2>Iniciar Sesión</h2>
                <input type="email" placeholder="Correo Electronico" name="correo" id="correo" required>
                <input type="password" placeholder="Contraseña" name="password" id="password" required>
                <button>Entrar</button>
              </form>
                <!--Formulario de Registro-->
              <form action="php_datos_db/Registro_usuario.php" method="POST" class="formulario__Registro">
                <h2>Registrarse</h2>
                <input type="text" placeholder="Nombre completo" name="nombre_completo" id="nombre_completo" required>
                <input type="email" placeholder="Correo Electronico" name="correo" id="correo" required>
                <input type="text" placeholder="Usuario" name="usuario" id="usuario" required>
                <input type="password" placeholder="Contraseña" name="password" id="password" required>
                <button>Registrarse</button>
              </form>
 
            </div>
        </div>

    </main>
<script src="js/JavaScript.js"></script>
</body>
</html>