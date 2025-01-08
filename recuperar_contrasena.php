<?php
include('login/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codEmpleado = (int)$_POST['codEmpleado'];

    // Conectar a la base de datos
    $conexion = conectar();

    if (!$conexion) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la conexión a la base de datos.']);
        exit();
    }

    // Verificar que el usuario existe
    $consultaUsuario = $conexion->prepare("SELECT idUsuario FROM usuarios WHERE codEmpleado = ?");
    $consultaUsuario->bind_param("i", $codEmpleado);
    $consultaUsuario->execute();
    $resultadoUsuario = $consultaUsuario->get_result();

    if ($resultadoUsuario->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
        exit();
    }

    $idUsuario = $resultadoUsuario->fetch_assoc()['idUsuario'];

    // Generar un nuevo código y su expiración
    $codigo = bin2hex(random_bytes(4)); // Genera un código aleatorio
    $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour')); // Código válido por 1 hora

    // Insertar el código de recuperación en la base de datos
    $consulta = $conexion->prepare("INSERT INTO recuperacion_codigos (idUsuario, codigo, expiracion) VALUES (?, ?, ?)");
    $consulta->bind_param("iss", $idUsuario, $codigo, $expiracion);

    if ($consulta->execute()) {
        echo json_encode(['status' => 'success', 'codigo' => $codigo, 'codEmpleado' => $codEmpleado]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar el código de recuperación.']);
    }

    $consulta->close();
    $conexion->close();
}
?>
