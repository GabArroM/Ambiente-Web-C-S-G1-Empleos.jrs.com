<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Autenticarse</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/Autenticacion.css">
</head>

<body>

  
        
        <?php include("nav.php") ?>
    

    <br>
    <div class="ContedorInicio">
        <h2>Iniciar Sesión</h2>
        
        <form  method="post"></form>
        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" required placeholder="Ingresa tu correo">

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">

        <button id="loginButton">Iniciar Sesión</button>

        <div class="RegistroLink">
            <p>¿No tienes una cuenta? <a href="Registro.php">Regístrate aquí</a></p>
        </div>
    </div>
    <br>
    <footer>
        <p>&copy; 2024 Derechos reservados Grupo#1.</p>
    </footer>

    <script src="js/Login.js"></script>
</body>

</html>
