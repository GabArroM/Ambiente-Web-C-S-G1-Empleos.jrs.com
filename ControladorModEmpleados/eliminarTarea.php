<?php
include('../Model/Conexion.php');

if (isset($_GET['id'])) {
    // id de la tarea a eliminar
    $taskId = $_GET['id'];

    
    $query = "DELETE FROM tareas WHERE id_tarea = '$taskId'";

    
    if ($conn->query($query) === TRUE) {
        
        header('Location: ../View/index.php');  // redirigir
    } else {
        echo "Error al eliminar tarea: " . $conn->error . "<br>";
    }
} 
?>
