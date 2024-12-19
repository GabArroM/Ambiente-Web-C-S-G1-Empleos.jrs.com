<?php
include("../Conexion/db.php");

if (isset($_GET['id'])) {
    $idOferta = $_GET['id'];

    // Consulta para eliminar la oferta
    $query = "DELETE FROM ofertas_empleo WHERE ID_Oferta = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $idOferta);

        if ($stmt->execute()) {
            header("Location: ModEmpleados.php");  // Redirigir al listado de ofertas
            exit();
        } else {
            echo "Error al eliminar oferta: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
    }
} else {
    echo "ID de oferta no especificado.";
}
?>

