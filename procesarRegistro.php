<?php
include('login/conexion.php');

// Inicializar la respuesta
$response = array('success' => false, 'message' => '');

// Obtener datos del formulario
$codEmpleado = $_POST['codEmpleado'];
$nombreUsuario = $_POST['nombreUsuario'];
$apePat = $_POST['apePat'];
$apeMat = $_POST['apeMat'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // contraseña con hash
$rol = $_POST['rol'];
$status = $_POST['status'];
$fechaRegistro = $_POST['fechaRegistro'];
$direccion = $_POST['direccion'];
$municipio = $_POST['municipio'];
$estado = $_POST['estado'];
$codigoPostal = $_POST['codigoPostal'];
$telefono = $_POST['telefono'];

// Conectar a la base de datos
$conn = conectar();

// Preparar y ejecutar la consulta
$sql = "INSERT INTO usuarios (codEmpleado, nombreUsuario, apePat, apeMat, eMail, contrasena, rol, status, fechaRegistro, direccion, municipio, estado, codigoPostal, telefono) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Verificar si la preparación de la consulta fue exitosa
if ($stmt) {
    $stmt->bind_param("isssssssssssss", $codEmpleado, $nombreUsuario, $apePat, $apeMat, $email, $password, $rol, $status, $fechaRegistro, $direccion, $municipio, $estado, $codigoPostal, $telefono);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Registro exitoso.';
    } else {
        $response['message'] = 'Error: ' . $stmt->error;
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    $response['message'] = 'Error al preparar la consulta.';
}

// Cerrar la conexión
$conn->close();

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>
