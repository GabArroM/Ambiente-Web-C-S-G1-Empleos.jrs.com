<!DOCTYPE html> 
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Buscar Empleo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/ModSolicitante.css?v=1.3">
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

$sql_ofertas = "SELECT o.ID_Oferta, o.Titulo, o.Descripcion, o.Categoria, o.TipoContrato, o.RangoSalarial, e.Empresa AS Empleador
                FROM Ofertas_Empleo o 
                JOIN Empleadores e ON o.ID_Empleador = e.ID_Empleador";
$stmt_ofertas = $conn->prepare($sql_ofertas);
$stmt_ofertas->execute();
$result_ofertas = $stmt_ofertas->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_oferta'])) {
    $id_oferta = $_POST['id_oferta'];
    $id_usuario_junior = $_SESSION['user_id']; 

    
    $sql_perfil = "SELECT p.ID_PerfilJunior, p.Telefono, p.CV_URL, u.Nombre, u.Email
                   FROM Perfil_Junior p
                   JOIN Usuarios u ON p.ID_Usuario = u.ID_Usuario
                   WHERE u.ID_Usuario = ?";
    $stmt_perfil = $conn->prepare($sql_perfil);
    $stmt_perfil->bind_param("i", $id_usuario_junior);
    $stmt_perfil->execute();
    $perfil = $stmt_perfil->get_result()->fetch_assoc();

   
    if (!$perfil) {
        echo "<h3>Debes completar tu perfil antes de aplicar.</h3>";
        exit();
    }

   
    $sql_verificar_aplicacion = "SELECT * FROM Aplicaciones WHERE ID_Oferta = ? AND ID_PerfilJunior = ?";
    $stmt_verificar = $conn->prepare($sql_verificar_aplicacion);
    $stmt_verificar->bind_param("ii", $id_oferta, $perfil['ID_PerfilJunior']);
    $stmt_verificar->execute();
    $resultado_aplicacion = $stmt_verificar->get_result();

    if ($resultado_aplicacion->num_rows > 0) {
        echo "<script>alert('Ya has aplicado a esta oferta de empleo anteriormente.');</script>";
        exit();
    }

    
    $sql_aplicar = "INSERT INTO Aplicaciones (ID_Oferta, ID_PerfilJunior, EstadoAplicacion) 
                    VALUES (?, ?, 'Aplicado')";
    $stmt_aplicar = $conn->prepare($sql_aplicar);
    $stmt_aplicar->bind_param("ii", $id_oferta, $perfil['ID_PerfilJunior']);
    if ($stmt_aplicar->execute()) {
        
        $response = [
            'success' => true,
            'mensaje' => '¡Has aplicado con éxito!'
        ];
    } else {
        $response = [
            'success' => false,
            'mensaje' => 'Hubo un error al intentar aplicar. Intenta nuevamente más tarde.'
        ];
    }
    echo json_encode($response);
    exit();
}
?>

<main>
    <section class="busquedaEmpleo">
        <h2>Búsqueda de Empleo</h2>
        <div class="boxBusqueda">
            <p>Aquí podrás buscar empleos disponibles.</p>
        </div>

        <section class="ofertasEmpleo">
            <h3>Ofertas de Empleo Disponibles</h3>
            <table class="offers-table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Tipo de Contrato</th>
                        <th>Rango Salarial</th>
                        <th></th>
                        <th>Aplicar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($oferta = $result_ofertas->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($oferta['Titulo']); ?></td>
                            <td><?php echo htmlspecialchars($oferta['Descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($oferta['Categoria']); ?></td>
                            <td><?php echo htmlspecialchars($oferta['TipoContrato']); ?></td>
                            <td><?php echo htmlspecialchars($oferta['RangoSalarial']); ?></td>
                            <td><?php echo htmlspecialchars($oferta['Empleador']); ?></td>
                            <td>
                                <form method="POST" id="form-<?php echo $oferta['ID_Oferta']; ?>" action="BusquedaEmpleo.php" onsubmit="return applyJob(event, <?php echo $oferta['ID_Oferta']; ?>)">
                                    <input type="hidden" name="id_oferta" value="<?php echo $oferta['ID_Oferta']; ?>">
                                    <button type="submit">Aplicar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </section>

    <div id="message-container" style="display: none;">
        <h3>¡Has aplicado con éxito!</h3>
        <div id="job-details"></div>
    </div>
</main>

<footer>
    Derechos reservados Grupo#1
</footer>

<script>
function applyJob(event, ofertaId) {
    event.preventDefault();

    var formData = new FormData(document.getElementById('form-' + ofertaId));

    fetch('BusquedaEmpleo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())  
    .then(data => {
        console.log(data); 
        if (data.success) {
          
            alert(data.mensaje); 
        } else {
            
            alert(data.mensaje);
        }
    })
    .catch(error => {
        console.error('Error en la solicitud:', error); 
        alert("Hubo un error al intentar aplicar. Intenta nuevamente más tarde.");
    });
}
</script>

</body>
</html>
