<?php
include('../Model/Conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //los datos de la tarea
    $taskId = $_POST['taskId']; 
    $username = $_POST['username'];
    $details = $_POST['details'];
    $state = $_POST['state'];

   
    $query = "UPDATE tareas SET username = '$username', details = '$details', state = '$state' WHERE id_tarea = '$taskId'";

   
    if ($conn->query($query) === TRUE) {
        
        header('Location: ../View/index.php');  //redirigir 
       
    } else {
        echo "Error al actualizar tarea: " . $conn->error . "<br>";
    }
}
?>
