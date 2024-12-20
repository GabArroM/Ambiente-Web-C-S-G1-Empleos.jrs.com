<?php
include("navbar.php");
include("./Conexion/db.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: Autenticarse.php'); 
    exit();
}

$user_id = $_SESSION['user_id']; 

$sqlPerfil = "SELECT ID_PerfilJunior FROM Perfil_Junior WHERE ID_Usuario = '$user_id'";
$resultPerfil = mysqli_query($conn, $sqlPerfil);
$perfilRow = mysqli_fetch_assoc($resultPerfil);
$id_perfilJunior = $perfilRow['ID_PerfilJunior'];

$sqlEmpresas = "SELECT ID_Empleador, Empresa FROM Empleadores";
$resultEmpresas = mysqli_query($conn, $sqlEmpresas);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empresa_id = $_POST['empresa_id'];
    $comentario = mysqli_real_escape_string($conn, $_POST['comentario']);

    if (!empty($comentario) && !empty($empresa_id)) {
        $sqlInsert = "INSERT INTO Comentarios (ID_Usuario, ID_PerfilJunior, ID_Empleador, Comentario) 
                      VALUES ('$user_id', '$id_perfilJunior', '$empresa_id', '$comentario')";
        if (mysqli_query($conn, $sqlInsert)) {
            $mensaje = "Comentario enviado exitosamente.";
        } else {
            $mensaje = "Hubo un error al enviar el comentario.";
        }
    } else {
        $mensaje = "Por favor, complete todos los campos.";
    }
}

$sqlComentarios = "SELECT c.Comentario, e.Empresa 
                   FROM Comentarios c
                   INNER JOIN Empleadores e ON c.ID_Empleador = e.ID_Empleador
                   WHERE c.ID_Usuario = '$user_id'";
$resultComentarios = mysqli_query($conn, $sqlComentarios);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comentarios</title>
    <link rel="stylesheet" href="./css/comentarios.css">
    <link rel="stylesheet" href="./css/style.css?v=1.4">
</head>
<body>
<main>
    <section class="comentarios-section">
        <h2>Deja tu comentario a una empresa</h2>
        
        <?php if (isset($mensaje)) { ?>
            <p><?php echo $mensaje; ?></p>
        <?php } ?>

        <form action="comentarios.php" method="POST">
            <label for="empresa">Selecciona la empresa:</label>
            <select name="empresa_id" id="empresa" required>
                <option value="">Selecciona una empresa</option>
                <?php
                while ($row = mysqli_fetch_assoc($resultEmpresas)) {
                    echo '<option value="' . $row['ID_Empleador'] . '">' . htmlspecialchars($row['Empresa']) . '</option>';
                }
                ?>
            </select>
            
            <label for="comentario">Escribe tu comentario:</label>
            <textarea name="comentario" id="comentario" rows="4" required></textarea>

            <button type="submit">Enviar Comentario</button>
        </form>

        <h3>Comentarios enviados:</h3>
        <?php if (mysqli_num_rows($resultComentarios) > 0) { ?>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($resultComentarios)) { ?>
                    <li>
                        <strong>Empresa:</strong> <?php echo htmlspecialchars($row['Empresa']); ?><br>
                        <strong>Comentario:</strong> <?php echo nl2br(htmlspecialchars($row['Comentario'])); ?>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No has enviado ningún comentario todavía.</p>
        <?php } ?>
    </section>
</main>
</body>

<footer>
        Derechos reservados Grupo#1
    </footer>
    
</html>
