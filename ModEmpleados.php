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
?>
<main>
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

    // Cerrar la conexión a la base de datos
    $conn->close();
    ?>
</section>
</main>

<footer>
    Derechos reservados Grupo#1
</footer>

</body>
</html>
