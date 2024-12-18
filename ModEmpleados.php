<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Modulo Empleados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/ModEmpleados.css">

</head>
<main>
    <?php

include("./Conexion/db.php");
include("nav.php");


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
    <section class="form-section">
        <h2 class="section-title">Gestionar Ofertas de Trabajo</h2>
        <div class="job-listings">
            <?php
            
            $sql = "SELECT * FROM ofertas_empleo WHERE id_empleador = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_empleador);
            

            if ($stmt->execute()) {
                $lista = $stmt->get_result();
            
                if ($lista->num_rows > 0) {
                    while ($row = $lista->fetch_assoc()) {
                        echo '<div class="job-card">';
                        echo '<h3>' . htmlspecialchars($row['titulo']) . '</h3>';
                        echo '<p><strong>Categoría:</strong> ' . htmlspecialchars($row['categoria']) . '</p>';
                        echo '<p><strong>Salario:</strong> ' . htmlspecialchars($row['rango_salarial']) . '</p>';
                        echo '<p><strong>Tipo de contrato:</strong> ' . htmlspecialchars($row['tipo_contrato']) . '</p>';
                        echo '<button class="submit-btn" onclick="window.location.href=\'editar_oferta.php?id=' . $row['id_oferta'] . '\'">Editar</button>';
                        echo '<button class="submit-btn" style="background-color: #dc3545;" onclick="window.location.href=\'eliminar_oferta.php?id=' . $row['id_oferta'] . '\'">Eliminar</button>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No hay ofertas hechas</p>';
                }
            } else {
                echo "Error al ejecutar la consulta: " . $stmt->error;
            }
            ?>
        </div>
    </section>
</main>
<footer>
    Derechos reservados Grupo#1
</footer>



</html>