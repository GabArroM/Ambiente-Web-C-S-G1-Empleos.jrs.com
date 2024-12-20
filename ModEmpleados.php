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


            $sql_nombre = "UPDATE Usuarios SET Nombre='$nombre' WHERE ID_Usuario='$user_id'";
            if ($conn->query($sql_nombre) !== TRUE) {
                echo "Error al actualizar nombre: " . $conn->error;
                exit();
            }


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


        if (isset($_POST['action']) && $_POST['action'] == 'delete') {
            if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'yes') {

                $sql_ofertas = "DELETE FROM ofertas_empleo WHERE ID_Empleador = 
                    (SELECT ID_Empleador FROM empleadores WHERE ID_Usuario = '$user_id')";
                if ($conn->query($sql_ofertas) === TRUE) {

                    $sql = "DELETE FROM empleadores WHERE ID_Usuario='$user_id'";
                    if ($conn->query($sql) === TRUE) {

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

        <?php
        $user_id = $_SESSION['user_id'];

        $sql_empleador = "SELECT id_empleador FROM empleadores WHERE id_usuario = ?";
        $stmt_empleador = $conn->prepare($sql_empleador);
        $stmt_empleador->bind_param("i", $user_id);
        $stmt_empleador->execute();
        $result_empleador = $stmt_empleador->get_result()->fetch_assoc();

        $id_empleador = $result_empleador['id_empleador'];
        ?>

        <section class="form-section">
            <h2 class="section-title">Crear Propuesta de Trabajo</h2>
            <form method="POST" action="./ControladorModEmpleados/AgregarOferta.php" id="jobProposalForm">
                <input type="hidden" id="id_empleador" name="id_empleador" value="<?php echo $id_empleador; ?>">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" placeholder="Título del Trabajo" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label><br>
                    <textarea id="descripcion" name="descripcion" rows="4" cols="50" placeholder="Describe la oferta de trabajo" required></textarea>
                </div>
                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <input type="text" id="categoria" name="categoria" placeholder="Categoría del Trabajo" required>
                </div>
                <div class="form-group">
                    <label for="tipo_contrato">Tipo de Contrato:</label>
                    <select id="tipo_contrato" name="tipo_contrato" required>
                        <option value="Medio tiempo">Medio tiempo</option>
                        <option value="Tiempo completo">Tiempo completo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rango_salarial">Rango Salarial:</label>
                    <input type="text" id="rango_salarial" name="rango_salarial" placeholder="Ejemplo: $1000 - $2000" required>
                </div>
                <button type="submit" class="submit-btn">Publicar Oferta</button>
            </form>
        </section>

        <section class="offers-list-section">
            <h2>Ofertas de Trabajo Publicadas</h2>
            <?php
            $sql_ofertas = "SELECT * FROM ofertas_empleo";
            $result_ofertas = $conn->query($sql_ofertas);

            if ($result_ofertas->num_rows > 0) {
                echo "<table class='offers-table'>
                <thead>
                    <tr>
                        <th>ID</th> <!-- Nueva columna para el ID -->
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Tipo de Contrato</th>
                        <th>Rango Salarial</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>";

                while ($row = $result_ofertas->fetch_assoc()) {
                    echo "<tr>
                    <td>" . htmlspecialchars($row['ID_Oferta']) . "</td> 
                    <td>" . htmlspecialchars($row['Titulo']) . "</td>
                    <td>" . htmlspecialchars($row['Descripcion']) . "</td>
                    <td>" . htmlspecialchars($row['Categoria']) . "</td>
                    <td>" . htmlspecialchars($row['TipoContrato']) . "</td>
                    <td>" . htmlspecialchars($row['RangoSalarial']) . "</td>
                    <td>
                        <a href='ControladorModEmpleados/editarTarea.php?id=" . $row['ID_Oferta'] . "'>Editar</a>
                        <a href='ControladorModEmpleados/eliminarTarea.php?id=" . $row['ID_Oferta'] . "'>Eliminar</a>
                    </td>
                </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No hay ofertas de trabajo disponibles.</p>";
            }


            $conn->close();
            ?>
        </section>
    </main>

    <footer>
        Derechos reservados Grupo#1
    </footer>

</body>

</html>