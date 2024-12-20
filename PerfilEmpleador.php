<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Modulo Empleados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css?v=1.3">
    <link rel="stylesheet" href="./css/ModEmpleados.css?v=1.3">
</head>

<body>

    <?php
    include("navbar.php");
    include("./Conexion/db.php");

    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['action']) && $_POST['action'] == 'update') {
            $nombre = $_POST['nombre'];
            $empresa = $_POST['empresa'];
            $ubicacion = $_POST['ubicacion'];

            // Actualización del nombre
            $sql_nombre = "UPDATE Usuarios SET Nombre='$nombre' WHERE ID_Usuario='$user_id'";
            if ($conn->query($sql_nombre) !== TRUE) {
                echo "Error al actualizar nombre: " . $conn->error;
                exit();
            }

            // Actualización del perfil del empleador
            $sql_perfil = "UPDATE empleadores SET 
                Empresa = ?, 
                Ubicacion = ?
                WHERE ID_Usuario = ?";

            $stmt = $conn->prepare($sql_perfil);
            $stmt->bind_param("ssi", $empresa, $ubicacion, $user_id);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Perfil actualizado correctamente.</div>";
            } else {
                echo "<div class='alert alert-danger'>Error al actualizar perfil: " . $stmt->error . "</div>";
            }
        }

        // Eliminar cuenta
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'yes') {

        // Primero eliminar los comentarios relacionados
        $sql_comentarios = "DELETE FROM comentarios WHERE ID_Empleador IN 
            (SELECT ID_Empleador FROM empleadores WHERE ID_Usuario = '$user_id')";
        if ($conn->query($sql_comentarios) === TRUE) {

            // Eliminar las aplicaciones relacionadas
            $sql_aplicaciones = "DELETE FROM aplicaciones WHERE ID_Oferta IN 
                (SELECT ID_Oferta FROM ofertas_empleo WHERE ID_Empleador = 
                (SELECT ID_Empleador FROM empleadores WHERE ID_Usuario = '$user_id'))";
            if ($conn->query($sql_aplicaciones) === TRUE) {

                // Eliminar ofertas de empleo
                $sql_ofertas = "DELETE FROM ofertas_empleo WHERE ID_Empleador = 
                    (SELECT ID_Empleador FROM empleadores WHERE ID_Usuario = '$user_id')";
                if ($conn->query($sql_ofertas) === TRUE) {

                    // Eliminar el perfil del empleador
                    $sql = "DELETE FROM empleadores WHERE ID_Usuario='$user_id'";
                    if ($conn->query($sql) === TRUE) {

                        // Eliminar el usuario
                        $sql_user = "DELETE FROM usuarios WHERE ID_Usuario='$user_id'";
                        if ($conn->query($sql_user) === TRUE) {
                            session_unset();
                            session_destroy();
                            header('Location: Autenticarse.php');
                            exit();
                        } else {
                            echo "<div class='alert alert-danger'>Error al eliminar la cuenta de usuario: " . $conn->error . "</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Error al eliminar el perfil: " . $conn->error . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Error al eliminar las ofertas de empleo: " . $conn->error . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Error al eliminar las aplicaciones: " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Error al eliminar los comentarios: " . $conn->error . "</div>";
        }
    }
}
    }

    $sql_user = "SELECT u.*, e.* FROM Usuarios u 
             LEFT JOIN empleadores e ON u.ID_Usuario = e.ID_Usuario 
             WHERE u.ID_Usuario = '$user_id'";
    $result = $conn->query($sql_user);
    $user_data = $result->fetch_assoc();
    ?>

    <main>
        <section class="form-section profile-section">
            <h2 class="section-title">Gestionar Perfil</h2>
            <form method="POST" action="" class="profile-form">
                <input type="hidden" name="action" value="update">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user_data['Nombre'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="empresa">Empresa:</label>
                    <input type="text" id="empresa" name="empresa" value="<?php echo htmlspecialchars($user_data['Empresa'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="ubicacion">Ubicación:</label>
                    <input type="text" id="ubicacion" name="ubicacion" value="<?php echo htmlspecialchars($user_data['Ubicacion'] ?? ''); ?>" required>
                </div>
                <button type="submit" class="submit-btn">Actualizar Perfil</button>
            </form>

            <form method="POST" action="" class="delete-form" onsubmit="return confirm('¿Está seguro de que desea eliminar su cuenta? Esta acción no se puede deshacer.');">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="confirm_delete" value="yes">
                <button type="submit" class="delete-btn">Eliminar Cuenta</button>
            </form>
        </section>
    </main>

    <footer>
        Derechos reservados Grupo#1
    </footer>

</body>

</html>
