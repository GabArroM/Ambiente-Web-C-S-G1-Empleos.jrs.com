<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Módulo Solicitantes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <link rel="stylesheet" href="./css/ModSolicitante.css?v=1.4">
    <link rel="stylesheet" href="./css/style.css?v=1.4">
    <link rel="stylesheet" href="./css/BuscarEmpleo.css?v=1.0">
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
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        // Limpiar datos para evitar XSS
        $nombre = htmlspecialchars($_POST['nombre']);
        $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
        $telefono = htmlspecialchars($_POST['telefono']);
        $educacion = htmlspecialchars($_POST['educacion']);
        $descripcion = htmlspecialchars($_POST['descripcion']);

        $cv_url = NULL; // Inicializar en NULL por defecto
        if ($_FILES['cv']['error'] == UPLOAD_ERR_OK) {
            $file_name = uniqid() . "_" . basename($_FILES['cv']['name']);
            $file_path = "uploads/" . $file_name;

            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $allowed_extensions = ['pdf', 'docx'];

            if (in_array(strtolower($file_extension), $allowed_extensions)) {
                if (move_uploaded_file($_FILES['cv']['tmp_name'], $file_path)) {
                    $cv_url = $file_path;
                } else {
                    echo "Error al subir el archivo.";
                    exit();
                }
            } else {
                echo "Archivo no permitido. Debe ser PDF o DOCX.";
                exit();
            }
        }

        // Actualizar nombre
        $sql_nombre = "UPDATE Usuarios SET Nombre=? WHERE ID_Usuario=?";
        $stmt = $conn->prepare($sql_nombre);
        $stmt->bind_param("si", $nombre, $user_id);
        if (!$stmt->execute()) {
            echo "Error al actualizar nombre: " . $conn->error;
            exit();
        }

        // Actualizar perfil
        $sql_perfil = "UPDATE Perfil_Junior SET Educacion=?, Habilidades=?, Telefono=?, CV_URL=? WHERE ID_Usuario=?";
        $stmt_perfil = $conn->prepare($sql_perfil);
        $stmt_perfil->bind_param("ssssi", $educacion, $descripcion, $telefono, $cv_url, $user_id);
        if ($stmt_perfil->execute()) {
            echo "Perfil actualizado correctamente.";
        } else {
            echo "Error al actualizar perfil: " . $conn->error;
        }
    }

    // Eliminar perfil
    if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'yes') {
        // Eliminar aplicaciones
        $sql_aplicaciones = "DELETE FROM Aplicaciones WHERE ID_PerfilJunior = (SELECT ID_PerfilJunior FROM Perfil_Junior WHERE ID_Usuario = ?)";
        $stmt_aplicaciones = $conn->prepare($sql_aplicaciones);
        $stmt_aplicaciones->bind_param("i", $user_id);
        if ($stmt_aplicaciones->execute()) {
            // Eliminar perfil junior
            $sql_perfil = "DELETE FROM Perfil_Junior WHERE ID_Usuario=?";
            $stmt_perfil = $conn->prepare($sql_perfil);
            $stmt_perfil->bind_param("i", $user_id);
            if ($stmt_perfil->execute()) {
                // Eliminar usuario
                $sql_user = "DELETE FROM Usuarios WHERE ID_Usuario=?";
                $stmt_user = $conn->prepare($sql_user);
                $stmt_user->bind_param("i", $user_id);
                if ($stmt_user->execute()) {
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
        } else {
            echo "Error al eliminar las aplicaciones: " . $conn->error;
        }
    }
}

// Obtener los datos del perfil
$sql = "SELECT u.Nombre, u.Email, p.Telefono, p.Educacion, p.Habilidades, p.CV_URL FROM Usuarios u JOIN Perfil_Junior p ON u.ID_Usuario = p.ID_Usuario WHERE u.ID_Usuario=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$perfil = $result->fetch_assoc();
?>

<section class="perfilSolicitante">
    <h2>Creación de Perfil Solicitante</h2>
    <div class="perfil-form-container">
        <form id="perfilForm" method="POST" action="ModSolicitantes.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($perfil['Nombre']); ?>" placeholder="Ingresa tu nombre completo" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($perfil['Email']); ?>" placeholder="Ingresa tu correo electrónico" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" name="telefono" id="telefono" value="<?php echo htmlspecialchars($perfil['Telefono']); ?>" placeholder="Ingresa tu teléfono" required>
            </div>
            <div class="form-group">
                <label for="educacion">Educación</label>
                <input type="text" name="educacion" id="educacion" value="<?php echo htmlspecialchars($perfil['Educacion']); ?>" placeholder="Ingresa tu nivel de educación" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Habilidades</label>
                <textarea name="descripcion" id="descripcion" rows="4" placeholder="Ingresa tus habilidades" required><?php echo htmlspecialchars($perfil['Habilidades']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="cv">Adjuntar CV (PDF, DOCX)</label>
                <input type="file" name="cv" id="cv" accept=".pdf,.docx">
                <?php if ($perfil['CV_URL']): ?>
                    <p>CV Actual: <a href="<?php echo $perfil['CV_URL']; ?>" target="_blank">Ver CV</a></p>
                <?php endif; ?>
            </div>
            <div class="form-actions">
                <button type="submit" name="action" value="update">Actualizar Perfil</button>
                <button type="button" name="action" value="delete" onclick="confirmDelete()">Borrar Perfil</button>
            </div>
        </form>
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
    <p>En este apartado podrás ver los puestos a los que has aplicado y su estado.</p>

    <?php
    // Mostrar postulaciones
    $sql_postulaciones = "
        SELECT O.Titulo AS Oferta, A.FechaAplicacion, A.EstadoAplicacion
        FROM Aplicaciones A
        INNER JOIN Ofertas_Empleo O ON A.ID_Oferta = O.ID_Oferta
        WHERE A.ID_PerfilJunior = (SELECT ID_PerfilJunior FROM Perfil_Junior WHERE ID_Usuario = ?)
    ";
    $stmt_postulaciones = $conn->prepare($sql_postulaciones);
    $stmt_postulaciones->bind_param("i", $user_id);
    $stmt_postulaciones->execute();
    $postulaciones = $stmt_postulaciones->get_result();
    ?>

    <table>
        <tr>
            <th>Oferta</th>
            <th>Fecha de Postulación</th>
            <th>Estado</th>
        </tr>
        <?php while ($row = $postulaciones->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['Oferta']); ?></td>
                <td><?php echo $row['FechaAplicacion']; ?></td>
                <td><?php echo $row['EstadoAplicacion']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

</main>

</body>
</html>
