<?php
// Archivo: generar_excel.php

// Incluir archivo de conexión a la base de datos
include('login/conexion.php');

// Obtener la conexión usando la función conectar()
$conex = conectar();

// Validar y obtener fechas desde los parámetros GET
if (isset($_GET['fecha_inicio'], $_GET['fecha_fin'], $_GET['tipo'])) {
    $fecha_inicio = $_GET['fecha_inicio'];
    $fecha_fin = $_GET['fecha_fin'];
    $tipo = $_GET['tipo']; // Obtener tipo de producto
} else {
    die("Datos no especificados.");
}
if (isset($_GET['fecha_inicio'], $_GET['fecha_fin'], $_GET['tipo'], $_GET['producto'])) {
    $fecha_inicio = $_GET['fecha_inicio'];
    $fecha_fin = $_GET['fecha_fin'];
    $tipo = $_GET['tipo']; // Obtener tipo de producto
    $productoSeleccionado = $_GET['producto']; // Obtener producto específico
} else {
    die("Datos no especificados.");
}
// Definir productos ALCALINOS, ACIDOS, NEUTROS, PERÓXIDOS, OTROS y ALCALINOS
$productos_alcalinos = ['S10', 'S11', 'S14', 'S20', 'S24'];
$productos_acidos = ['S02', 'S03', 'S04', 'S12', 'S18', 'S25'];
$productos_neutros = ['S07', 'S08', 'S09', 'S13', 'S15', 'S29', 'S31'];
$productos_peroxidos = ['S23', 'S26', 'S23B'];
$productos_otros = ['S05', 'S27', 'S28', 'S32', 'S33'];
$productos_mmpp = ['S30'];

$consulta_salidas = "SELECT * FROM salida WHERE fecha BETWEEN ? AND ? AND producto = ? ORDER BY fecha";
$stmt_salidas = mysqli_prepare($conex, $consulta_salidas);
mysqli_stmt_bind_param($stmt_salidas, "sss", $fecha_inicio, $fecha_fin, $productoSeleccionado);
mysqli_stmt_execute($stmt_salidas);
$resultado_salidas = mysqli_stmt_get_result($stmt_salidas);

$consulta_entradas = "SELECT * FROM entrada WHERE fecha BETWEEN ? AND ? AND producto = ? ORDER BY fecha";
$stmt_entradas = mysqli_prepare($conex, $consulta_entradas);
mysqli_stmt_bind_param($stmt_entradas, "sss", $fecha_inicio, $fecha_fin, $productoSeleccionado);
mysqli_stmt_execute($stmt_entradas);
$resultado_entradas = mysqli_stmt_get_result($stmt_entradas);

// Filtrar productos según el tipo seleccionado
function filtrar_productos($producto, $tipo, $productos_neutros, $productos_acidos, $productos_otros, $productos_peroxidos, $productos_mmpp, $productos_alcalinos) {
    if (empty($tipo)) {
        return true; // Si no hay filtro de tipo, incluir todos los productos
    }

    switch ($tipo) {
        case 'NEUTROS':
            return in_array($producto, $productos_neutros);
        case 'ACIDOS':
            return in_array($producto, $productos_acidos);
        case 'OTROS':
            return in_array($producto, $productos_otros);
        case 'PEROXIDOS':
            return in_array($producto, $productos_peroxidos);
        case 'MMPP':
            return in_array($producto, $productos_mmpp);
        case 'ALCALINOS':
            return in_array($producto, $productos_alcalinos);
        default:
            return false; // Si el tipo no coincide, no incluir el producto
    }
}


// Calcular totales de salidas y entradas
$total_salidas = 0;
while ($row = mysqli_fetch_assoc($resultado_salidas)) {
    if (filtrar_productos($row['producto'], $tipo, $productos_neutros, $productos_acidos, $productos_otros, $productos_peroxidos, $productos_mmpp, $productos_alcalinos)) {
        $total_salidas += $row['cantidadSalida'];
    }
}

$total_entradas = 0;
while ($row = mysqli_fetch_assoc($resultado_entradas)) {
    if (filtrar_productos($row['producto'], $tipo, $productos_neutros, $productos_acidos, $productos_otros, $productos_peroxidos, $productos_mmpp, $productos_alcalinos)) {
        $total_entradas += $row['cantidadEntrada'];
    }
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

require 'vendor/autoload.php'; // RUTA PARA HACER ARCHIVO EXCEL

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Crear una nueva instancia de Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Agregar el logo
$logoPath = 'imagenes/aguidalogo.png'; // Ruta del archivo del logo
$drawing = new Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo');
$drawing->setPath($logoPath);
$drawing->setCoordinates('A1'); // Coordenada donde se coloca el logo
$drawing->setOffsetX(10); // Ajuste horizontal (en píxeles)
$drawing->setOffsetY(10); // Ajuste vertical (en píxeles)
$drawing->setWidth(120); // Ancho del logo (en píxeles)
$drawing->setHeight(60); // Alto del logo (en píxeles)
$drawing->setWorksheet($sheet); // Agregar el logo a la hoja activa

// Establecer el título del documento
$sheet->mergeCells('A1:J1');
$sheet->setCellValue('A1', 'REPORTE DE ENTRADAS Y SALIDAS');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Agregar periodo del reporte
$sheet->mergeCells('A2:J2');
$sheet->setCellValue('A2', 'PERIODO: ' . htmlspecialchars($fecha_inicio) . ' - ' . htmlspecialchars($fecha_fin));
$sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Agregar información del tipo de producto
$sheet->mergeCells('A3:J3');
$sheet->setCellValue('A3', 'Informe de Movimientos: ' . htmlspecialchars($tipo));
$sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
// Agregar información del producto específico
$sheet->mergeCells('A4:J4');
$sheet->setCellValue('A4', 'Producto: ' . htmlspecialchars($productoSeleccionado));
$sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Estilo para los encabezados de tabla
$headerStyle = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFDDDDDD']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Estilo para las filas de datos
$dataStyle = [
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Estilo para las secciones
$sectionStyle = [
    'font' => ['bold' => true, 'size' => 14],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFFFE0']]
];

// Estilo para la cantidad final
$finalAmountStyle = [
    'font' => ['bold' => true, 'size' => 14],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFC6EFCE']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Agregar datos de salidas
$sheet->setCellValue('A5', 'Salidas');
$sheet->mergeCells('A5:J5');
$sheet->getStyle('A5')->applyFromArray($sectionStyle);

$sheet->setCellValue('A6', 'Cod');
$sheet->setCellValue('B6', 'Fecha');
$sheet->setCellValue('C6', 'Nombre del Pesador');
$sheet->setCellValue('D6', 'Nombre del Remitente');
$sheet->setCellValue('E6', 'Área');
$sheet->setCellValue('F6', 'Nombre del Receptor');
$sheet->setCellValue('G6', 'Turno');
$sheet->setCellValue('H6', 'Producción Realizada');
$sheet->setCellValue('I6', 'Producto');
$sheet->setCellValue('J6', 'Cantidad');

// Aplicar estilo a los encabezados
$sheet->getStyle('A6:J6')->applyFromArray($headerStyle);

$rowIndex = 7;
$contador_salidas = 1;
while ($row = mysqli_fetch_assoc($resultado_salidas)) {
    if (filtrar_productos($row['producto'], $tipo, $productos_neutros, $productos_acidos, $productos_otros, $productos_peroxidos, $productos_mmpp, $productos_alcalinos)) {
        $sheet->setCellValue('A' . $rowIndex, $contador_salidas);
        $sheet->setCellValue('B' . $rowIndex, htmlspecialchars($row['fecha']));
        $sheet->setCellValue('C' . $rowIndex, htmlspecialchars($row['nombrePesador']));
        $sheet->setCellValue('D' . $rowIndex, htmlspecialchars($row['nombreEntrego']));
        $sheet->setCellValue('E' . $rowIndex, htmlspecialchars($row['area']));
        $sheet->setCellValue('F' . $rowIndex, htmlspecialchars($row['nombreReceptor']));
        $sheet->setCellValue('G' . $rowIndex, htmlspecialchars($row['turno']));
        $sheet->setCellValue('H' . $rowIndex, htmlspecialchars($row['produccionRealizada']));
        $sheet->setCellValue('I' . $rowIndex, htmlspecialchars($row['producto']));
        $sheet->setCellValue('J' . $rowIndex, htmlspecialchars($row['cantidadSalida']));
        $rowIndex++;
        $contador_salidas++;
    }
}
$sheet->setCellValue('I' . $rowIndex, 'Total Salidas:');
$sheet->setCellValue('J' . $rowIndex, $total_salidas);
$sheet->getStyle('I' . $rowIndex . ':J' . $rowIndex)->applyFromArray($sectionStyle);

// Agregar datos de entradas
$rowIndex += 2;
$sheet->setCellValue('A' . $rowIndex, 'Entradas');
$sheet->mergeCells('A' . $rowIndex . ':J' . $rowIndex);
$sheet->getStyle('A' . $rowIndex)->applyFromArray($sectionStyle);

$sheet->setCellValue('A' . ($rowIndex + 1), 'Cod');
$sheet->setCellValue('B' . ($rowIndex + 1), 'Fecha');
$sheet->setCellValue('C' . ($rowIndex + 1), 'Nombre del Proveedor');
$sheet->setCellValue('D' . ($rowIndex + 1), 'Nombre del Receptor');
$sheet->setCellValue('E' . ($rowIndex + 1), 'Área');
$sheet->setCellValue('F' . ($rowIndex + 1), 'Turno');
$sheet->setCellValue('G' . ($rowIndex + 1), 'Producción Realizada');
$sheet->setCellValue('H' . ($rowIndex + 1), 'Producto');
$sheet->setCellValue('I' . ($rowIndex + 1), 'Cantidad');

// Aplicar estilo a los encabezados
$sheet->getStyle('A' . ($rowIndex + 1) . ':I' . ($rowIndex + 1))->applyFromArray($headerStyle);

$rowIndex += 2;
$contador_entradas = 1;
while ($row = mysqli_fetch_assoc($resultado_entradas)) {
    if (filtrar_productos($row['producto'], $tipo, $productos_neutros, $productos_acidos, $productos_otros, $productos_peroxidos, $productos_mmpp, $productos_alcalinos)) {
        $sheet->setCellValue('A' . $rowIndex, $contador_entradas);
        $sheet->setCellValue('B' . $rowIndex, htmlspecialchars($row['fecha']));
        $sheet->setCellValue('C' . $rowIndex, htmlspecialchars($row['nombreProveedor']));
        $sheet->setCellValue('D' . $rowIndex, htmlspecialchars($row['nombreReceptor']));
        $sheet->setCellValue('E' . $rowIndex, htmlspecialchars($row['area']));
        $sheet->setCellValue('F' . $rowIndex, htmlspecialchars($row['turno']));
        $sheet->setCellValue('G' . $rowIndex, htmlspecialchars($row['produccionRealizada']));
        $sheet->setCellValue('H' . $rowIndex, htmlspecialchars($row['producto']));
        $sheet->setCellValue('I' . $rowIndex, htmlspecialchars($row['cantidadEntrada']));
        $rowIndex++;
        $contador_entradas++;
    }
}
$sheet->setCellValue('H' . $rowIndex, 'Total Entradas:');
$sheet->setCellValue('I' . $rowIndex, $total_entradas);
$sheet->getStyle('H' . $rowIndex . ':I' . $rowIndex)->applyFromArray($sectionStyle);

// Agregar balance de inventario
$rowIndex += 2;
$sheet->setCellValue('H' . $rowIndex, 'Balance de Inventario');
$sheet->mergeCells('H' . $rowIndex . ':I' . $rowIndex);
$sheet->getStyle('H' . $rowIndex)->applyFromArray($finalAmountStyle);
$sheet->setCellValue('J' . $rowIndex, $cantidad_final);
$sheet->getStyle('J' . $rowIndex)->applyFromArray($finalAmountStyle);

// Aplicar estilo a las filas de datos
$sheet->getStyle('A7:J' . ($rowIndex - 1))->applyFromArray($dataStyle);

// Ajustar el ancho de las columnas para que se ajuste al contenido
$columnWidths = [
    'A' => 10, // Ajusta estos valores según sea necesario
    'B' => 15,
    'C' => 21,
    'D' => 21,
    'E' => 15,
    'F' => 20,
    'G' => 21,
    'H' => 20,
    'I' => 15,
    'J' => 15
];

foreach ($columnWidths as $column => $width) {
    $sheet->getColumnDimension($column)->setWidth($width);
}

// Crear el archivo Excel
$writer = new Xlsx($spreadsheet);
$filename = 'reporte_' . date('Y-m-d_H-i-s') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>
