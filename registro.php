<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="./css/style.css?v=1.3">
    <link rel="stylesheet" href="css/Registro.css?v=1.4">
</head>
<body>
    <?php include("navbar.php") ?>
    
        <div class="header-content" >
            
            
        </div>
    
    
    <main>
        <div class="ContenedorInicio">
            <div id="notification"></div> 
            
            <form id="registroForm">
            <h1>Registro de Usuario</h1>
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" required>
                
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                
                <label for="tipoUsuario">Selecciona tu perfil:</label>
                <select id="tipoUsuario" name="tipoUsuario" required>
                    <option value="Junior">Solicitante</option>
                    <option value="Empleador">Empleador</option>
                </select>
                
                <button type="submit">Registrar</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Derechos reservados Grupo#1.</p>
    </footer>

    <script>
        document.getElementById('registroForm').addEventListener('submit', function(event) {
            event.preventDefault(); 
            
            const formData = new FormData(this); 

            fetch('registroControl.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) 
            .then(data => {
                const notification = document.getElementById('notification');
                
                if (data.status == 'success') {
                    notification.innerHTML = `<div class="notification success">${data.message}</div>`;
                    
                    
                } else {
                    notification.innerHTML = `<div class="notification error">${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>

</body>
</html>
