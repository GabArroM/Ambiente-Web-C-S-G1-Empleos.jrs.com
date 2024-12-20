<?php

include('./Conexion/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_empleador= $_POST['id_empleador'];  
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria'];
    $tipo_contrato = $_POST['tipo_contrato'];
    $rango_salarial = $_POST['rango_salarial'];
    
    
    if (empty($titulo) || empty($descripcion) || empty($categoria) || empty($tipo_contrato) || empty($rango_salarial)) {
        die("Error: Todos los campos son obligatorios.");
    }
       
        
        $sql_oferta = "INSERT INTO ofertas_empleo (ID_Empleador, Titulo, Descripcion, Categoria, TipoContrato, RangoSalarial) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt_oferta = $conn->prepare($sql_oferta)) {
            $stmt_oferta->bind_param("isssss", $id_empleador, $titulo, $descripcion, $categoria, $tipo_contrato, $rango_salarial);
            
            if ($stmt_oferta->execute()) {
                
                header("Location: ModEmpleados.php"); 
                echo "<script>alert('Oferta insertada correctamente.');</script>";
            } else {
                echo "Error al insertar la oferta: " . $stmt_oferta->error;
            }
    
            $stmt_oferta->close();
        } else {
            echo "Error al preparar la consulta para la oferta: " . $conn->error;
        }
    
    
   
    
    
    $conn->close();
}
?>
