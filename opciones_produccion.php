<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdaguidasan";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener las opciones de la base de datos
$sql = "SELECT value, label FROM opciones_produccion";
$result = $conn->query($sql);

// Iterar sobre los resultados y crear opciones para el select
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . htmlspecialchars($row['value']) . '">' . htmlspecialchars($row['label']) . '</option>';
    }
} else {
    echo '<option value="">No hay producciones disponibles</option>';
}

// Cerrar conexión
$conn->close();
?>
