<?php
include("conexion.php");
$conn = conectar();
session_start();

// Asegúrate de que la respuesta sea en formato JSON
header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigoUsuario = $_POST['codEmpleado'];
    $codigoContrasena = $_POST['password'];

    // Consulta para obtener el hash de la contraseña
    $sql = "SELECT nombreUsuario, contrasena FROM usuarios WHERE codEmpleado = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codigoUsuario); // Asumiendo que codEmpleado es un entero
    $stmt->execute();
    $stmt->store_result();

    // Verificar si se encontró un registro
    if ($stmt->num_rows > 0) {
        // Vincular el resultado de la consulta
        $stmt->bind_result($nombreUsuario, $contrasenaAlmacenada);
        $stmt->fetch();
        
        // Comparar la contraseña proporcionada con el hash almacenado
        if (password_verify($codigoContrasena, $contrasenaAlmacenada)) {
            // La contraseña es correcta
            $_SESSION['username'] = $nombreUsuario; // Guardar el nombre de usuario en la sesión
            $response['status'] = 'success';
            $response['message'] = 'Inicio de sesión exitoso';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Contraseña incorrecta.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Empleado no encontrado.';
    }

    $stmt->close();
    $conn->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de solicitud no válido.';
}

echo json_encode($response);
?>
