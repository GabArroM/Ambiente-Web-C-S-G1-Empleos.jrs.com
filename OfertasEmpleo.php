<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ofertas de Empleo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/ModEmpleados.css?v=1.3">
    <link rel="stylesheet" href="./css/style.css?v=1.4">
</head>

<body>
<?php
include("navbar.php");
include("./Conexion/db.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: Autenticarse.php'); 
    exit();
}

$id_empleador = $_SESSION['user_id'];

// Aceptar o rechazar aplicaciones
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'], $_POST['id_aplicacion'])) {
    $id_aplicacion = $_POST['id_aplicacion'];
    $nuevo_estado = $_POST['action'] == 'accept' ? 'Aceptada' : 'Rechazada';

    // Actualizar estado de la aplicación
    $sql_update = "UPDATE Aplicaciones SET Estado = ? WHERE ID_Aplicacion = ? AND ID_Oferta IN 
                    (SELECT ID_Oferta FROM Ofertas_Empleo WHERE ID_Empleador = ?)";
    if ($stmt_update = $conn->prepare($sql_update)) {
        $stmt_update->bind_param("sii", $nuevo_estado, $id_aplicacion, $id_empleador);
        $stmt_update->execute();
        $stmt_update->close();
    }
}

// Consultar ofertas de empleo
$sql_ofertas = "SELECT o.ID_Oferta, o.Titulo, o.Descripcion, o.Categoria, o.TipoContrato, o.RangoSalarial 
                FROM Ofertas_Empleo o WHERE o.ID_Empleador = ?";
$stmt_ofertas = $conn->prepare($sql_ofertas);
$stmt_ofertas->bind_param("i", $id_empleador);
$stmt_ofertas->execute();
$result_ofertas = $stmt_ofertas->get_result();
?>

<main>
    <h2>Mis Ofertas de Empleo</h2>

    <?php while ($oferta = $result_ofertas->fetch_assoc()): ?>
        <section class="oferta">
            <h3><?php echo htmlspecialchars($oferta['Titulo']); ?></h3>
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($oferta['Descripcion']); ?></p>
            <p><strong>Categoría:</strong> <?php echo htmlspecialchars($oferta['Categoria']); ?></p>
            <p><strong>Tipo de Contrato:</strong> <?php echo htmlspecialchars($oferta['TipoContrato']); ?></p>
            <p><strong>Rango Salarial:</strong> <?php echo htmlspecialchars($oferta['RangoSalarial']); ?></p>

            <h4>Aplicaciones Recibidas</h4>
            <?php 
            // Consultar aplicaciones para esta oferta
            $sql_aplicaciones = "SELECT a.ID_Aplicacion, u.Nombre, u.Email, p.Telefono, p.CV_URL, a.Estado 
                                 FROM Aplicaciones a
                                 JOIN Usuarios u ON a.ID_Usuario = u.ID_Usuario
                                 JOIN Perfil_Junior p ON a.ID_Usuario = p.ID_Usuario
                                 WHERE a.ID_Oferta = ?";
            $stmt_aplicaciones = $conn->prepare($sql_aplicaciones);
            $stmt_aplicaciones->bind_param("i", $oferta['ID_Oferta']);
            $stmt_aplicaciones->execute();
            $result_aplicaciones = $stmt_aplicaciones->get_result();

            // Verificar si hay aplicaciones antes de mostrar
            if ($result_aplicaciones->num_rows > 0):
            ?>
                <ul>
                    <?php while ($aplicacion = $result_aplicaciones->fetch_assoc()): ?>
                        <li>
                            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($aplicacion['Nombre']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($aplicacion['Email']); ?></p>
                            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($aplicacion['Telefono']); ?></p>
                            <p><strong>Estado:</strong> <?php echo htmlspecialchars($aplicacion['Estado']); ?></p>
                            <?php if ($aplicacion['CV_URL']): ?>
                                <p><a href="<?php echo $aplicacion['CV_URL']; ?>" target="_blank">Ver CV</a></p>
                            <?php endif; ?>
                            
                            <form method="POST" action="">
                                <input type="hidden" name="id_aplicacion" value="<?php echo $aplicacion['ID_Aplicacion']; ?>">
                                <button type="submit" name="action" value="accept">Aceptar</button>
                                <button type="submit" name="action" value="reject">Rechazar</button>
                            </form>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No hay aplicaciones para esta oferta.</p>
            <?php endif; ?>
            <?php $stmt_aplicaciones->close(); ?>
        </section>
    <?php endwhile; ?>

    <?php $stmt_ofertas->close(); ?>
</main>

<footer>
    Derechos reservados Grupo#1
</footer>
</body>
</html>
