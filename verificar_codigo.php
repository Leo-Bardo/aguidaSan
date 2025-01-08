<?php
include('login/conexion.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['codigo']) || !isset($_POST['codigo_ingresado']) || !isset($_POST['codEmpleado'])) {
        echo json_encode(['status' => 'error', 'message' => 'Datos insuficientes.']);
        exit();
    }

    $codigo = $_POST['codigo'];
    $codigo_ingresado = $_POST['codigo_ingresado'];
    $codEmpleado = (int)$_POST['codEmpleado'];

    $conn = conectar();
    if ($conn === false) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la conexión a la base de datos: ' . $conn->connect_error]);
        exit();
    }

    $consulta = $conn->prepare("SELECT id FROM recuperacion_codigos WHERE codigo = ? AND expiracion > NOW()");
    if ($consulta === false) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
        exit();
    }

    $consulta->bind_param("s", $codigo_ingresado);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Código verificado correctamente.', 'codEmpleado' => $codEmpleado]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Código de verificación inválido o expirado.']);
    }

    $consulta->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido.']);
}
?>
