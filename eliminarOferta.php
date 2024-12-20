<?php
include("./Conexion/db.php");

if (isset($_GET['id'])) {
    $idOferta = $_GET['id'];

    $query1 = "DELETE FROM aplicaciones WHERE ID_Oferta = ?";
    $query = "DELETE FROM ofertas_empleo WHERE ID_Oferta = ?";
    if ($stmt1 = $conn->prepare($query1)) {
        $stmt1->bind_param("i", $idOferta);
        if (!$stmt1->execute()) {
            echo "Error al eliminar aplicaciones relacionadas: " . $stmt1->error;
            $stmt1->close();
            exit();
        }
        $stmt1->close();
    } else {
        echo "Error al preparar la consulta para eliminar aplicaciones: " . $conn->error;
        exit();
    }

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
        echo "Error al preparar la consulta para eliminar oferta: " . $conn->error;
    }
} else {
    echo "ID de oferta no especificado.";
}
?>


