[9:04 a. m., 17/7/2024] Nahum: REPORTE DE SANITIZANTES
[9:05 a. m., 17/7/2024] Nahum: <?php
// Archivo: mostrar_registros.php

// Incluir archivo de conexión a la base de datos
require_once("cons_db.php");

// Validar y obtener fechas desde los parámetros GET
if (isset($_GET['fecha_inicio'], $_GET['fecha_fin'])) {
    $fecha_inicio = $_GET['fecha_inicio'];
    $fecha_fin = $_GET['fecha_fin'];
} else {
    die("Fechas no especificadas.");
}

// Consulta para obtener las salidas dentro del rango de fechas especificado
$consulta_salidas = "SELECT * 
                     FROM salida 
                     WHERE fecha BETWEEN ? AND ?
                     ORDER BY fecha";

$stmt_salidas = mysqli_prepare($conex, $consulta_salidas);
mysqli_stmt_bind_param($stmt_salidas, "ss", $fecha_inicio, $fecha_fin);
mysqli_stmt_execute($stmt_salidas);
$resultado_salidas = mysqli_stmt_get_result($stmt_salidas);

// Consulta para obtener las entradas dentro del rango de fechas especificado
$consulta_entradas = "SELECT * 
                      FROM entrada 
                      WHERE fecha BETWEEN ? AND ?
                      ORDER BY fecha";

$stmt_entradas = mysqli_prepare($conex, $consulta_entradas);
mysqli_stmt_bind_param($stmt_entradas, "ss", $fecha_inicio, $fecha_fin);
mysqli_stmt_execute($stmt_entradas);
$resultado_entradas = mysqli_stmt_get_result($stmt_entradas);

// Calcular totales de salidas y entradas
$total_salidas = 0;
while ($row = mysqli_fetch_assoc($resultado_salidas)) {
    $total_salidas += $row['cantidadSalida'];
}

$total_entradas = 0;
while ($row = mysqli_fetch_assoc($resultado_entradas)) {
    $total_entradas += $row['cantidadEntrada'];
}

// Volver a ejecutar las consultas para reiniciar el cursor
mysqli_stmt_execute($stmt_salidas);
$resultado_salidas = mysqli_stmt_get_result($stmt_salidas);

mysqli_stmt_execute($stmt_entradas);
$resultado_entradas = mysqli_stmt_get_result($stmt_entradas);

// Cerrar statements
mysqli_stmt_close($stmt_salidas);
mysqli_stmt_close($stmt_entradas);

// Cerrar conexión a la base de datos
mysqli_close($conex);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Entradas y Salidas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto; /* Permitir desplazamiento horizontal */
        }

        .header, .section {
            margin-bottom: 20px;
        }

        .header h1, .header h2, .section h3 {
            margin: 0;
            padding: 5px;
            background-color: #e2e2e2;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
        }

        .header h2 {
            font-size: 20px;
        }

        .section h3 {
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: auto;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        @media screen and (max-width: 768px) {
            .header h1 {
                font-size: 20px;
            }

            .header h2 {
                font-size: 18px;
            }

            .section h3 {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Incluir la barra de navegación -->
    <div id="navbar-container"></div>

    <div class="container">
        <div class="header">
            <h1>REPORTE DE ENTRADAS Y SALIDAS</h1>
            <h2>PERIODO: <?php echo htmlspecialchars($fecha_inicio); ?> - <?php echo htmlspecialchars($fecha_fin); ?></h2>
        </div>
        
        <div class="section">
            <h3>Salidas</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Nombre del Pesador</th>
                        <th>Nombre del que Entrega</th>
                        <th>Área</th>
                        <th>Nombre del que Recibe</th>
                        <th>Turno</th>
                        <th>Producción Realizada</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $contador_salidas = 1;
                    while ($row = mysqli_fetch_assoc($resultado_salidas)) { ?>
                        <tr>
                            <td data-label="ID"><?php echo $contador_salidas; ?></td>
                            <td data-label="Fecha"><?php echo htmlspecialchars($row['fecha']); ?></td>
                            <td data-label="Nombre del Pesador"><?php echo htmlspecialchars($row['nombrePesador']); ?></td>
                            <td data-label="Nombre del que Entrega"><?php echo htmlspecialchars($row['nombreEntrego']); ?></td>
                            <td data-label="Área"><?php echo htmlspecialchars($row['area']); ?></td>
                            <td data-label="Nombre del que Recibe"><?php echo htmlspecialchars($row['nombreReceptor']); ?></td>
                            <td data-label="Turno"><?php echo htmlspecialchars($row['turno']); ?></td>
                            <td data-label="Producción Realizada"><?php echo htmlspecialchars($row['produccionRealizada']); ?></td>
                            <td data-label="Producto"><?php echo htmlspecialchars($row['producto']); ?></td>
                            <td data-label="Cantidad"><?php echo htmlspecialchars($row['cantidadSalida']); ?></td>
                        </tr>
                        <?php 
                        $contador_salidas++;
                    } ?>
                    <tr>
                        <td colspan="9" style="text-align: right; font-weight: bold;">Total Salidas:</td>
                        <td><?php echo $total_salidas; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="section">
            <h3>Entradas</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Nombre del Proveedor</th>
                        <th>Nombre del que Recibe</th>
                        <th>Área</th>
                        <th>Turno</th>
                        <th>Producción Realizada</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $contador_entradas = 1;
                    while ($row = mysqli_fetch_assoc($resultado_entradas)) { ?>
                        <tr>
                            <td data-label="ID"><?php echo $contador_entradas; ?></td>
                            <td data-label="Fecha"><?php echo htmlspecialchars($row['fecha']); ?></td>
                            <td data-label="Nombre del Proveedor"><?php echo htmlspecialchars($row['nombreProveedor']); ?></td>
                            <td data-label="Nombre del que Recibe"><?php echo htmlspecialchars($row['nombreReceptor']); ?></td>
                            <td data-label="Área"><?php echo htmlspecialchars($row['area']); ?></td>
                            <td data-label="Turno"><?php echo htmlspecialchars($row['turno']); ?></td>
                            <td data-label="Producción Realizada"><?php echo htmlspecialchars($row['produccionRealizada']); ?></td>
                            <td data-label="Producto"><?php echo htmlspecialchars($row['producto']); ?></td>
                            <td data-label="Cantidad"><?php echo htmlspecialchars($row['cantidadEntrada']); ?></td>
                        </tr>
                        <?php 
                        $contador_entradas++;
                    } ?>
                    <tr>
                        <td colspan="8" style="text-align: right; font-weight: bold;">Total Entradas:</td>
                        <td><?php echo $total_entradas; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Scripts al final del cuerpo del documento -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar la barra de navegación desde un archivo externo
            fetch('navbar.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('navbar-container').innerHTML = data;

                    // Una vez cargada la barra de navegación, añadir el event listener para el menú
                    const menuIcon = document.querySelector('.menu-icon');
                    const drawer = document.getElementById('drawer');
                    const overlay = document.querySelector('.overlay');
                    const submenuItems = document.querySelectorAll('.submenu-label');
                    const logoutButton = document.querySelector('.logout-button');

                    menuIcon.addEventListener('click', function() {
                        drawer.classList.toggle('open');
                        overlay.classList.toggle('active');
                    });

                    overlay.addEventListener('click', function() {
                        drawer.classList.remove('open');
                        overlay.classList.remove('active');
                    });

                    submenuItems.forEach(item => {
                        item.addEventListener('click', function() {
                            this.querySelector('.submenu-content').classList.toggle('active');
                        });
                    });

                    logoutButton.addEventListener('click', function() {
                        Swal.fire({
                            title: '¿Cerrar sesión?',
                            text: '¿Estás seguro de que quieres cerrar sesión?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sí, cerrar sesión'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Aquí puedes redirigir al usuario a la página de inicio de sesión
                                window.location.href = 'pagina_de_inicio.html';
                            }
                        });
                    });
                })
                .catch(error => console.error('Error al cargar la barra de navegación:', error));
        });
    </script>
</body>
</html>