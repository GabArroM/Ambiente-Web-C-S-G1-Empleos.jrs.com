<?php
include("./Conexion/db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM ofertas_empleo WHERE ID_Oferta = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "No se encontró la oferta de trabajo.";
        exit;
    }
} else {
    echo "ID no proporcionado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editar Oferta</title>
    <link rel="stylesheet" href="./css/style.css?v=1.3"> 
    <link rel="stylesheet" href="./css/formulario.css">
</head>

<?php 
include("./navbar.php");
?>

    <body>
        <main>
            <section class="form-section">
                <h2 class="section-title">Editar Propuesta de Trabajo</h2>
                <form method="POST" action="actualizarOferta.php">
                    <input type="hidden" name="id" value="<?php echo $row['ID_Oferta']; ?>">
                    <div class="form-group">
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($row['Titulo']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label><br>
                        <textarea id="descripcion" name="descripcion" rows="4" cols="50" required><?php echo htmlspecialchars($row['Descripcion']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoría:</label>
                        <input type="text" id="categoria" name="categoria" value="<?php echo htmlspecialchars($row['Categoria']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tipo_contrato">Tipo de Contrato:</label>
                        <select id="tipo_contrato" name="tipo_contrato" required>
                            <option value="Medio tiempo" <?php if ($row['TipoContrato'] == "Medio tiempo") echo "selected"; ?>>Medio tiempo</option>
                            <option value="Tiempo completo" <?php if ($row['TipoContrato'] == "Tiempo completo") echo "selected"; ?>>Tiempo completo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rango_salarial">Rango Salarial:</label>
                        <input type="text" id="rango_salarial" name="rango_salarial" value="<?php echo htmlspecialchars($row['RangoSalarial']); ?>" required>
                    </div>
                    <button type="submit" class="submit-btn">Actualizar Oferta</button>
                </form>
            </section>
        </main>
        <footer>
            Derechos reservados Grupo#1
        </footer>
    </body>
</html>
