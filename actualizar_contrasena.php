<?php
include('login/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['codEmpleado']) || !isset($_POST['nueva_contrasena'])) {
        echo json_encode(['status' => 'error', 'message' => 'Datos insuficientes.']);
        exit();
    }

    $codEmpleado = (int)$_POST['codEmpleado'];
    $nuevaContrasena = $_POST['nueva_contrasena'];

    // Encriptar la nueva contraseña
    $nuevaContrasenaHash = password_hash($nuevaContrasena, PASSWORD_BCRYPT);

    $conn = conectar();

    if ($conn === false) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la conexión a la base de datos.']);
        exit();
    }

    // Actualizar la contraseña en la base de datos
    $consulta = $conn->prepare("UPDATE usuarios SET contrasena = ? WHERE codEmpleado = ?");
    $consulta->bind_param("si", $nuevaContrasenaHash, $codEmpleado);

    if ($consulta->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Contraseña actualizada exitosamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la contraseña.']);
    }

    $consulta->close();
    $conn->close();
}
?>
