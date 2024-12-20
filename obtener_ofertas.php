<?php
include("./Conexion/db.php");

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit();
}

$id_empleador = $_SESSION['user_id'];

$sql_ofertas = "SELECT * FROM Ofertas_Empleo WHERE ID_Empleador = $id_empleador";
$result_ofertas = mysqli_query($conn, $sql_ofertas);

if (!$result_ofertas) {
    echo json_encode(['error' => 'Error en la consulta SQL: ' . mysqli_error($conn)]);
    exit();
}

$ofertas = [];
if (mysqli_num_rows($result_ofertas) > 0) {
    // Tu código para procesar las ofertas sigue aquí...
} else {
    echo json_encode(['error' => 'No se encontraron ofertas.']);
    exit();
}

if (mysqli_num_rows($result_ofertas) > 0) {
    while ($row_oferta = mysqli_fetch_assoc($result_ofertas)) {
        // Obtener las postulaciones de cada oferta
        $sql_postulaciones = "SELECT p.ID_Usuario, p.Educacion, p.Habilidades, p.Telefono, u.Nombre 
                              FROM Aplicaciones a
                              JOIN Perfil_Junior p ON a.ID_PerfilJunior = p.ID_PerfilJunior
                              JOIN Usuarios u ON p.ID_Usuario = u.ID_Usuario
                              WHERE a.ID_Oferta = " . $row_oferta['ID_Oferta'] . " AND a.EstadoAplicacion = 'Aplicado'";
        $result_postulaciones = mysqli_query($conn, $sql_postulaciones);

        $postulaciones = [];
        if (mysqli_num_rows($result_postulaciones) > 0) {
            while ($row_postulacion = mysqli_fetch_assoc($result_postulaciones)) {
                $postulaciones[] = $row_postulacion;
            }
        }

        // Agregar la oferta y sus postulaciones al array
        $ofertas[] = [
            'ID_Oferta' => $row_oferta['ID_Oferta'],
            'Titulo' => $row_oferta['Titulo'],
            'Descripcion' => $row_oferta['Descripcion'],
            'Categoria' => $row_oferta['Categoria'],
            'RangoSalarial' => $row_oferta['RangoSalarial'],
            'TipoContrato' => $row_oferta['TipoContrato'],
            'FechaPublicacion' => $row_oferta['FechaPublicacion'],
            'postulaciones' => $postulaciones
        ];
    }
}

// Enviar la respuesta en formato JSON
echo json_encode(['ofertas' => $ofertas]);

?>
