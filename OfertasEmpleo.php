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
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit();
}

$id_empleador = $_SESSION['user_id'];
echo json_encode(['id_empleador' => $id_empleador]);  
?>

<main>
    <h2>Mis Ofertas de Empleo</h2>
    
    <div id="ofertas-container"></div>

    <script>
        fetch('obtener_ofertas.php')
    .then(response => response.json())
    .then(data => {
        console.log(data);  

        const ofertasContainer = document.getElementById('ofertas-container');
        
        if (data.error) {
            ofertasContainer.innerHTML = "<p>" + data.error + "</p>";
            return;
        }

        if (data.ofertas.length > 0) {
            data.ofertas.forEach(oferta => {
                let ofertaHTML = `
                    <div class="oferta">
                        <h3>${oferta.Titulo}</h3>
                        <p>${oferta.Descripcion}</p>
                        <p><strong>Categoría:</strong> ${oferta.Categoria}</p>
                        <p><strong>Rango Salarial:</strong> ${oferta.RangoSalarial}</p>
                        <p><strong>Tipo de Contrato:</strong> ${oferta.TipoContrato}</p>
                        <p><strong>Fecha de Publicación:</strong> ${oferta.FechaPublicacion}</p>
                        <h4>Postulaciones:</h4>
                `;
                
                if (oferta.postulaciones.length > 0) {
                    oferta.postulaciones.forEach(postulante => {
                        ofertaHTML += `
                            <div class="postulante">
                                <p><strong>Nombre:</strong> ${postulante.Nombre}</p>
                                <p><strong>Educación:</strong> ${postulante.Educacion}</p>
                                <p><strong>Habilidades:</strong> ${postulante.Habilidades}</p>
                                <p><strong>Teléfono:</strong> ${postulante.Telefono}</p>
                            </div>
                        `;
                    });
                } else {
                    ofertaHTML += "<p>No hay postulaciones para esta oferta.</p>";
                }
                ofertaHTML += "</div>";

                ofertasContainer.innerHTML += ofertaHTML;
            });
        } else {
            ofertasContainer.innerHTML = "<p>No tienes ofertas de empleo publicadas.</p>";
        }
    })
    .catch(error => {
        console.error('Error al cargar las ofertas:', error);
    });

    </script>
</main>

<footer>
    Derechos reservados Grupo#1
</footer>

</body>
</html>
