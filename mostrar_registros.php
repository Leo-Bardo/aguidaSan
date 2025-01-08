<?php
// Archivo: mostrar_registros.php

// Incluir archivo de conexión a la base de datos
include('login/conexion.php');

// Obtener la conexión usando la función conectar()
$conex = conectar();

// Validar y obtener fechas y tipo de producto desde los parámetros GET
if (isset($_GET['fecha_inicio'], $_GET['fecha_fin'], $_GET['tipo'], $_GET['producto'])) {
    $fecha_inicio = $_GET['fecha_inicio'];
    $fecha_fin = $_GET['fecha_fin'];
    $tipo = $_GET['tipo']; // Obtener tipo de producto
    $productoSeleccionado = $_GET['producto']; // Obtener producto específico
} else {
    die("Datos no especificados.");
}

// Definir productos NEUTROS, ACIDOS, OTROS, PERÓXIDOS, MMPP y ALCALINOS
$productos_alcalinos = ['S10', 'S11', 'S14', 'S20', 'S24'];
$productos_acidos = ['S02', 'S03', 'S04', 'S12', 'S18', 'S25'];
$productos_neutros = ['S07', 'S08', 'S09', 'S13', 'S15', 'S29', 'S31'];
$productos_peroxidos = ['S23', 'S26', 'S23B'];
$productos_otros = ['S05', 'S27', 'S28', 'S32', 'S33'];
$productos_mmpp = ['S30'];

// Consulta para obtener las salidas dentro del rango de fechas especificado
$consulta_salidas = "SELECT * FROM salida WHERE fecha BETWEEN ? AND ? AND producto = ? ORDER BY fecha";
$stmt_salidas = mysqli_prepare($conex, $consulta_salidas);
mysqli_stmt_bind_param($stmt_salidas, "sss", $fecha_inicio, $fecha_fin, $productoSeleccionado);
mysqli_stmt_execute($stmt_salidas);
$resultado_salidas = mysqli_stmt_get_result($stmt_salidas);

// Consulta para obtener las entradas dentro del rango de fechas especificado
$consulta_entradas = "SELECT * FROM entrada WHERE fecha BETWEEN ? AND ? AND producto = ? ORDER BY fecha";
$stmt_entradas = mysqli_prepare($conex, $consulta_entradas);
mysqli_stmt_bind_param($stmt_entradas, "sss", $fecha_inicio, $fecha_fin, $productoSeleccionado);
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

// Calcular la cantidad final
$cantidad_final = $total_entradas - $total_salidas;

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
    <link rel="stylesheet" type="text/css" href="estilos/registros.css">
</head>
<body>
<div class="container">
    <!-- Botón para regresar -->
    <a href="menureportes.php" class="btn-regresar">Regresar</a>
    
    <!-- Botón para generar el archivo Excel -->
    <form action="generar_excel.php" method="get" id="formGenerarExcel">
        <input type="hidden" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
        <input type="hidden" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>">
        <input type="hidden" name="tipo" value="<?php echo htmlspecialchars($tipo); ?>">
        <input type="hidden" name="producto" value="<?php echo htmlspecialchars($productoSeleccionado); ?>">
        <button type="submit" id="btnGenerarExcel" class="btn-generar-excel">Generar Excel</button>
    </form>

    <div class="container">
        <div class="header">
            <h1>REPORTE DE ENTRADAS Y SALIDAS</h1>
            <h2>PERIODO: <?php echo htmlspecialchars($fecha_inicio); ?> - <?php echo htmlspecialchars($fecha_fin); ?></h2>
            <h3>Informe de Movimientos: <?php echo htmlspecialchars($tipo); ?> - Producto: <?php echo htmlspecialchars($productoSeleccionado); ?></h3>
        </div>
        <div class="section">
    <h3>Salidas</h3>
    <div class="table-wrapper">
        <div class="section">
            <table>
                <thead>
                    <tr>
                        <th>Cod</th>
                        <th>Fecha</th>
                        <th>Nombre del Pesador</th>
                        <th>Nombre del Remitente</th>
                        <th>Área</th>
                        <th>Nombre del Receptor</th>
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
                            <td><?php echo $contador_salidas; ?></td>
                            <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombrePesador']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombreEntrego']); ?></td>
                            <td><?php echo htmlspecialchars($row['area']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombreReceptor']); ?></td>
                            <td><?php echo htmlspecialchars($row['turno']); ?></td>
                            <td><?php echo htmlspecialchars($row['produccionRealizada']); ?></td>
                            <td><?php echo htmlspecialchars($row['producto']); ?></td>
                            <td><?php echo htmlspecialchars($row['cantidadSalida']); ?></td>
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
        </div>
        
        <div class="section">
    <h3>Entradas</h3>
    <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Cod</th>
                        <th>Fecha</th>
                        <th>Nombre del Proveedor</th>
                        <th>Nombre del Receptor</th>
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
                            <td><?php echo $contador_entradas; ?></td>
                            <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombreProveedor']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombreReceptor']); ?></td>
                            <td><?php echo htmlspecialchars($row['area']); ?></td>
                            <td><?php echo htmlspecialchars($row['turno']); ?></td>
                            <td><?php echo htmlspecialchars($row['produccionRealizada']); ?></td>
                            <td><?php echo htmlspecialchars($row['producto']); ?></td>
                            <td><?php echo htmlspecialchars($row['cantidadEntrada']); ?></td>
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
        
        <div class="section">
    <h3>Cantidad Final</h3>
    <div class="table-wrapper">
            
            <table>
                <tr>
                    <td style="text-align: right; font-weight: bold;">Balance de Inventario: </td>
                    <td><?php echo $cantidad_final; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Botón Generar Excel
    const formGenerarExcel = document.getElementById('formGenerarExcel');
    formGenerarExcel.addEventListener('submit', function(event) {
        event.preventDefault();
        
        Swal.fire({
            title: '¿Deseas generar el archivo Excel?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, generar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                formGenerarExcel.submit();
            }
        });
    });

    // Botón Regresar
    const btnRegresar = document.querySelector('.btn-regresar');
    btnRegresar.addEventListener('click', function(event) {
        event.preventDefault();

        Swal.fire({
            title: '¿Deseas regresar al menú de reportes?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, regresar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = btnRegresar.getAttribute('href');
            }
        });
    });
});
</script>
</body>
</html>