<?php
include("navbar.php");
include("./Conexion/db.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: Autenticarse.php'); 
    exit();
}

$empleador_id = $_SESSION['user_id']; 

$sqlEmpresa = "SELECT ID_Empleador, Empresa FROM Empleadores WHERE ID_Usuario = '$empleador_id'";
$resultEmpresa = mysqli_query($conn, $sqlEmpresa);

if (mysqli_num_rows($resultEmpresa) == 0) {
    echo "<p>No se ha encontrado ninguna empresa asociada a tu cuenta.</p>";
    exit();
}

$empresaRow = mysqli_fetch_assoc($resultEmpresa);
$empresa_id = $empresaRow['ID_Empleador'];
$empresa_nombre = $empresaRow['Empresa'];

$sqlComentarios = "SELECT c.Comentario, u.ID_Usuario, u.Nombre, p.Telefono
                   FROM Comentarios c
                   INNER JOIN Perfil_Junior p ON c.ID_PerfilJunior = p.ID_PerfilJunior
                   INNER JOIN Usuarios u ON p.ID_Usuario = u.ID_Usuario
                   WHERE c.ID_Empleador = '$empresa_id'";

$resultComentarios = mysqli_query($conn, $sqlComentarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comentarios de la Empresa</title>
    <link rel="stylesheet" href="./css/comentarios.css">
    <link rel="stylesheet" href="./css/style.css?v=1.4">
</head>
<body>

<main>
    <section class="comentarios-empleador-section">
        <h2>Comentarios sobre la empresa: <?php echo htmlspecialchars($empresa_nombre); ?></h2>
        
        <?php if (mysqli_num_rows($resultComentarios) > 0) { ?>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($resultComentarios)) { ?>
                    <li>
                        <strong>Usuario:</strong> <?php echo htmlspecialchars($row['Nombre']); ?><br>
                        <strong>Comentario:</strong> <?php echo nl2br(htmlspecialchars($row['Comentario'])); ?><br>
                        <strong>Teléfono:</strong> <?php echo htmlspecialchars($row['Telefono']); ?>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No se han recibido comentarios para esta empresa.</p>
        <?php } ?>
    </section>
</main>
</body>

<footer>
        Derechos reservados Grupo#1
    </footer>
</html>

