<?php
include("./Conexion/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_oferta = isset($_POST['id_oferta']) ? $_POST['id_oferta'] : '';
    $id_user = isset($_POST['id_user']) ? $_POST['id_user'] : '';
    $id_perfil = isset($_POST['id_perfil']) ? $_POST['id_perfil'] : '';

    if (!empty($id_oferta) && !empty($id_user) && !empty($id_perfil)) {
        $estado_aplicacion = 'Aplicado'; 
        $sql = "INSERT INTO Aplicaciones (ID_Oferta, ID_PerfilJunior, EstadoAplicacion) VALUES ('$id_oferta', '$id_perfil', '$estado_aplicacion')";

        if (mysqli_query($conn, $sql)) {
            echo "Aplicación exitosa.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Datos incompletos.";
    }
}
?>
