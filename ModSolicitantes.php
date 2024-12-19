<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Módulo Solicitantes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <link rel="stylesheet" href="./css/ModSolicitante.css?v=1.3">
    <link rel="stylesheet" href="./css/style.css?v=1.4">
</head>

<body>
<?php 
include("navbar.php");
include("./Conexion/db.php");
?>
    <main>
         <?php 
                

if (!isset($_SESSION['user_id'])) {
    header('Location: Autenticarse.php'); 
    exit();
}

$user_id = $_SESSION['user_id']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // actualizar perfil
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $educacion = $_POST['educacion'];
        $descripcion = $_POST['descripcion'];
        
        if ($_FILES['cv']['error'] == UPLOAD_ERR_OK) {
            $file_name = uniqid() . "_" . basename($_FILES['cv']['name']);
            $file_path = "uploads/" . $file_name;

            if (move_uploaded_file($_FILES['cv']['tmp_name'], $file_path)) {
                $cv_url = $file_path;
            } else {
                echo "Error al subir el archivo.";
                exit();
            }
        } else {
            $cv_url = NULL;  // Si no se sube un archivo, asignar NULL
        }

        $sql_nombre = "UPDATE Usuarios SET Nombre='$nombre' WHERE ID_Usuario='$user_id'";
        if ($conn->query($sql_nombre) !== TRUE) {
            echo "Error al actualizar nombre: " . $conn->error;
            exit();
        }

        $sql_perfil = "UPDATE Perfil_Junior SET Educacion='$educacion', Habilidades='$descripcion', Telefono='$telefono', CV_URL='$cv_url' WHERE ID_Usuario='$user_id'";

        if ($conn->query($sql_perfil) === TRUE) {
            echo "Perfil actualizado correctamente.";
        } else {
            echo "Error al actualizar perfil: " . $conn->error;
        }
    }

    // Eliminar perfil y usuario
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'yes') {
            $sql = "DELETE FROM Perfil_Junior WHERE ID_Usuario='$user_id'";
            if ($conn->query($sql) === TRUE) {
                $sql_user = "DELETE FROM usuarios WHERE ID_Usuario='$user_id'";
                if ($conn->query($sql_user) === TRUE) {
                    session_unset();
                    session_destroy();
                    header('Location: Autenticarse.php');
                    exit();
                } else {
                    echo "Error al eliminar la cuenta de usuario: " . $conn->error;
                }
            } else {
                echo "Error al eliminar el perfil: " . $conn->error;
            }
        }
    }
}

// Consulta para obtener los datos del perfil
$sql = "SELECT u.Nombre, u.Email, p.Telefono, p.Educacion, p.Habilidades, p.CV_URL FROM Usuarios u JOIN Perfil_Junior p ON u.ID_Usuario = p.ID_Usuario WHERE u.ID_Usuario='$user_id'";
$result = $conn->query($sql);
$perfil = $result->fetch_assoc();
?>

<section class="perfilSolicitante">
    <h2>Creación de Perfil Solicitante</h2>
    <div class="dropdown">
        <button class="dropbtn">Editar Perfil</button>
        <div class="dropdown-content">
            <form id="perfilForm" method="POST" action="ModSolicitantes.php" enctype="multipart/form-data">
                <label for="nombre">Nombre Completo</label><br>
                <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($perfil['Nombre']); ?>" placeholder="Ingresa tu nombre completo" required><br>
                <label for="correo">Correo Electrónico</label><br>
                <input type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($perfil['Email']); ?>" placeholder="Ingresa tu correo electrónico" required><br>
                <label for="telefono">Teléfono</label><br>
                <input type="tel" name="telefono" id="telefono" value="<?php echo htmlspecialchars($perfil['Telefono']); ?>" placeholder="Ingresa tu teléfono" required><br>
                <label for="educacion">Educación</label><br>
                <input type="text" name="educacion" id="educacion" value="<?php echo htmlspecialchars($perfil['Educacion']); ?>" placeholder="Ingresa tu nivel de educación" required><br>
                <label for="descripcion">Habilidades</label><br>
                <textarea name="descripcion" id="descripcion" rows="4" cols="50" placeholder="Ingresa tus habilidades" required><?php echo htmlspecialchars($perfil['Habilidades']); ?></textarea><br>
                <label for="cv">Adjuntar CV (PDF, DOCX)</label><br>
                <input type="file" name="cv" id="cv" accept=".pdf,.docx"><br>
                <?php if ($perfil['CV_URL']): ?>
                    <p>CV Actual: <a href="<?php echo $perfil['CV_URL']; ?>" target="_blank">Ver CV</a></p>
                <?php endif; ?>
                
                <button type="submit" name="action" value="update">Actualizar Perfil</button>
                <button type="button" name="action" value="delete" onclick="confirmDelete()">Borrar Perfil</button><br>
            </form>
        </div>
    </div>
</section>

<script>
    
    function confirmDelete() {
        if (confirm("¿Estás seguro de que deseas eliminar tu perfil? Esta acción no se puede deshacer.")) {
            if (confirm("¿Seguro que deseas eliminar tu cuenta? Esta acción también eliminará tu cuenta de usuario y cerrará sesión.")) {
                var form = document.getElementById('perfilForm');
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'confirm_delete';
                input.value = 'yes';
                form.appendChild(input);

                var actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete';
                form.appendChild(actionInput);

                form.submit(); 
            }
        }
    }
</script>



        <section class="postulaciones">
            <h2>Postulaciones</h2>
            <div class="dropdown">
                <button class="dropbtn">Ver Postulaciones</button>
                <div class="dropdown-content">
                    <p>En este apartado podrás ver los puestos a los que has aplicado y su estado.</p>
                    <ul>
                        <li>Puesto: Desarrollador Web - Estado: En revisión</li>
                        <li>Puesto: Diseñador Gráfico - Estado: Postulación rechazada</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="estadisticas">
            <h2>Seguimiento de Estadísticas</h2>
            <div class="boxEstadisticas">
                <p>Aquí se mostrarán las estadísticas de las postulaciones y empleos aplicados.</p>
            </div>
        </section>
    </main>
    <footer>
        Derechos reservados Grupo#1
    </footer>
</body>

</html>