<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Empleo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/ModSolicitantes.css?v=1.3">
    <link rel="stylesheet" href="./css/BuscarEmpleo.css?v=1.0">
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

$user_id = $_SESSION['user_id']; 

$sqlPerfil = "SELECT ID_PerfilJunior FROM Perfil_Junior WHERE ID_Usuario = '$user_id'";
$resultPerfil = mysqli_query($conn, $sqlPerfil);
$perfilRow = mysqli_fetch_assoc($resultPerfil);
$id_perfilJunior = $perfilRow['ID_PerfilJunior'];


$sql = "SELECT * FROM Ofertas_Empleo";
$result = mysqli_query($conn, $sql);
?>

<main>
    <section class="busquedaEmpleo">
        <h2>Búsqueda de Empleo</h2>
        <div class="boxBusqueda">
            <p>Aquí podrás buscar empleos disponibles.</p>

            <?php
            if (mysqli_num_rows($result) > 0) {
                echo '<table border = "0">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th>Rango Salarial</th>
                                <th>Tipo de Contrato</th>
                                <th>Fecha de Publicación</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                            <td>' . htmlspecialchars($row['Titulo']) . '</td>
                            <td>' . htmlspecialchars($row['Descripcion']) . '</td>
                            <td>' . htmlspecialchars($row['Categoria']) . '</td>
                            <td>' . htmlspecialchars($row['RangoSalarial']) . '</td>
                            <td>' . htmlspecialchars($row['TipoContrato']) . '</td>
                            <td>' . $row['FechaPublicacion'] . '</td>
                            <td>
                                <button type="button" id="aplicar-btn-' . $row['ID_Oferta'] . '" class="aplicar-btn" data-oferta-id="' . $row['ID_Oferta'] . '" data-perfil-id="' . $id_perfilJunior . '">Aplicar</button>
                            </td>
                          </tr>';
                }
                
                echo '</tbody></table>';
            } else {
                echo "<p>No hay ofertas de empleo disponibles en este momento.</p>";
            }
            ?>
        </div>
    </section>
    <div id="mensaje-aplicacion" style="display: none;">
    <p>¡Has aplicado a la oferta con éxito!</p>
</div>
</main>

<footer>
    Derechos reservados Grupo#1
</footer>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll('.aplicar-btn');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const ofertaId = this.getAttribute('data-oferta-id');
            const perfilId = this.getAttribute('data-perfil-id');
            const userId = <?php echo json_encode($user_id); ?>; 

            fetch('aplicar_accion.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_oferta=${ofertaId}&id_user=${userId}&id_perfil=${perfilId}`
            })
            .then(response => response.text())
            .then(data => {
                const mensajeDiv = document.getElementById('mensaje-aplicacion');
                mensajeDiv.style.display = 'block'; 
            })
            .catch(error => {
                console.error('Error al aplicar:', error);
            });
        });
    });
});
</script>

</body>
</html>
