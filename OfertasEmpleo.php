<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Postulaciones Recibidas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/ModEmpleados.css?v=1.6">
    <link rel="stylesheet" href="./css/BuscarEmpleo.css?v=1.5">
    <link rel="stylesheet" href="./css/style.css?v=1.7">
</head>

<body>
<?php
include("navbar.php");
include("./Conexion/db.php"); 

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'], $_POST['id_aplicacion'])) {
    $accion = $_POST['accion'];
    $id_aplicacion = $_POST['id_aplicacion'];

    $valores_permitidos = ['Aplicado', 'En revisión', 'Rechazado', 'Aprobado'];

    if (in_array($accion, $valores_permitidos)) {
        $sql_update = "UPDATE Aplicaciones SET EstadoAplicacion = ? WHERE ID_Aplicacion = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $accion, $id_aplicacion);
        $stmt_update->execute();
    }
}

$sql = "
    SELECT A.ID_Aplicacion, O.Titulo AS Oferta, U.Nombre AS Postulante, U.Email, 
           P.Educacion, P.Habilidades, P.CV_URL, A.FechaAplicacion, A.EstadoAplicacion
    FROM Aplicaciones A
    INNER JOIN Ofertas_Empleo O ON A.ID_Oferta = O.ID_Oferta
    INNER JOIN Empleadores E ON O.ID_Empleador = E.ID_Empleador
    INNER JOIN Perfil_Junior P ON A.ID_PerfilJunior = P.ID_PerfilJunior
    INNER JOIN Usuarios U ON P.ID_Usuario = U.ID_Usuario
    WHERE E.ID_Usuario = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main>
    <h2>Postulaciones Recibidas</h2>
    <table border="1">
        <tr>
            <th>ID Aplicación</th>
            <th>Oferta de Empleo</th>
            <th>Postulante</th>
            <th>Email</th>
            <th>Educación</th>
            <th>Habilidades</th>
            <th>CV</th>
            <th>Fecha de Aplicación</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>

        <?php 
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['ID_Aplicacion']}</td>
                        <td>{$row['Oferta']}</td>
                        <td>{$row['Postulante']}</td>
                        <td>{$row['Email']}</td>
                        <td>{$row['Educacion']}</td>
                        <td>{$row['Habilidades']}</td>
                        <td><a class='aplicar-btn' href='{$row['CV_URL']}' target='_blank'>Ver CV</a></td>
                        <td>{$row['FechaAplicacion']}</td>
                        <td>{$row['EstadoAplicacion']}</td>
                        <td>
                            <form method='POST' style='display: inline-block;'>
                                <input type='hidden' name='id_aplicacion' value='{$row['ID_Aplicacion']}'>
                                <button class='aplicar-btn'type='submit' name='accion' value='Rechazado'>Rechazar</button>
                                <button class='aplicar-btn'type='submit type='submit' name='accion' value='En revisión'>Revisión</button>
                                <button class='aplicar-btn'type='submit type='submit' name='accion' value='Aprobado'>Aprobar</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No tienes postulaciones recibidas.</td></tr>";
        }
        ?>
    </table>
</main>

<footer>
    Derechos reservados Grupo#1
</footer>

</body>
</html>
