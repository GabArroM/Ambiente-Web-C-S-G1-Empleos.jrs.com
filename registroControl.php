<?php
session_start(); // Inicia la sesión

// Habilitar la visualización de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configuración de conexión a la base de datos
include("./Conexion/db.php");

$response = array('status' => '', 'message' => ''); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $tipoUsuario = $_POST['tipoUsuario'];

    if (empty($nombre) || empty($email) || empty($password) || empty($tipoUsuario)) {
        $response['status'] = 'error';
        $response['message'] = 'Todos los campos son obligatorios.';
    } else {
        
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        
        $sql_check_email = "SELECT * FROM Usuarios WHERE Email = ?";
        $stmt_check = $conn->prepare($sql_check_email);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Este correo electrónico ya está registrado.';
        } else {
           
            $sql = "INSERT INTO Usuarios (Nombre, Email, Contraseña, TipoUsuario) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nombre, $email, $passwordHash, $tipoUsuario);

            if ($stmt->execute()) {
                $userId = $stmt->insert_id; 

                if ($tipoUsuario == "Empleador") {
                    
                    $sqlEmpleador = "INSERT INTO empleadores (id_usuario) VALUES (?)";
                    $stmtEmpleador = $conn->prepare($sqlEmpleador);
                    $stmtEmpleador->bind_param("i", $userId);
                    $stmtEmpleador->execute();
                } elseif ($tipoUsuario == "Junior") {
                  
                    $sqlJunior = "INSERT INTO perfil_junior (id_usuario) VALUES (?)";
                    $stmtJunior = $conn->prepare($sqlJunior);
                    $stmtJunior->bind_param("i", $userId);
                    $stmtJunior->execute();
                }

            
                $response['status'] = 'success';
                $response['message'] = 'Usuario creado exitosamente. Puedes iniciar sesión.';
            } else {
              
                $response['status'] = 'error';
                $response['message'] = 'Error en el registro: ' . $stmt->error;
            }

            $stmt->close();
        }
    }

   
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

$conn->close();
?>
