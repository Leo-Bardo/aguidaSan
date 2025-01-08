<!DOCTYPE html>
<html>
<head>
    <title>Ingresar Código de Recuperación</title>
    <link rel="stylesheet" href="src/sweetalert2.min.css">
    <script src="src/sweetalert2.min.js"></script>
</head>
<body>
    <h1><b>Ingresa tu código de verificación</b></h1>
    <form action="verificar_codigo.php" method="post">
        <input type="hidden" name="codEmpleado" value="<?php echo htmlspecialchars($_GET['codEmpleado'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <input type="text" name="codigo_ingresado" placeholder="Introduce el código de verificación" required>
        <button type="submit">Verificar Código</button>
    </form>
</body>
</html>
