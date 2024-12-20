<?php
include("./Conexion/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria'];
    $tipo_contrato = $_POST['tipo_contrato'];
    $rango_salarial = $_POST['rango_salarial'];

    $sql = "UPDATE ofertas_empleo 
            SET Titulo = ?, Descripcion = ?, Categoria = ?, TipoContrato = ?, RangoSalarial = ? 
            WHERE ID_Oferta = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $titulo, $descripcion, $categoria, $tipo_contrato, $rango_salarial, $id);

    if ($stmt->execute()) {
        echo "Oferta actualizada con éxito.";
        header("Location: ./ModEmpleados.php"); // Redirigir de vuelta al módulo principal
        exit;
    } else {
        echo "Error al actualizar la oferta: " . $conn->error;
    }
}
?>
