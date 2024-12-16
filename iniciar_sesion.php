<?php
// Configuración de conexión a la base de datos
include("./Conexion/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM Usuarios WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['Contraseña'])) {
            session_start();
            $_SESSION['user_id'] = $user['ID_Usuario'];
            $_SESSION['user_name'] = $user['Nombre'];
            $_SESSION['user_email'] = $user['Email'];
            $_SESSION['user_type'] = $user['TipoUsuario'];

            echo json_encode([
                "success" => true,
                "tipoUsuario" => $user['TipoUsuario']
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Contraseña incorrecta."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Usuario no encontrado."
        ]);
    }

    $conn->close();
}
?>
