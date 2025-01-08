<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Registros - AGUIDA</title>
    <link rel="stylesheet" type="text/css" href="estilos/menureportes.css">
</head>
<body>
    <div class="container">
        <h1>Consulta de Reportes</h1>
        <form id="report-form" action="mostrar_registros.php" method="GET">
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio">

            <label for="fecha_fin">Fecha de Fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin">

            <label for="tipo">Opciones de Reportes:</label>
            <select id="tipo" name="tipo">
                <option value="">-selecciona-</option>
                <option value="REPORTE-GENERAL">REPORTE-GENERAL</option>
                <option value="ALCALINOS">REPORTE-Alcalinos</option>
                <option value="ACIDOS">REPORTE-Ácidos</option>
                <option value="NEUTROS">REPORTE-Neutros</option>
                <option value="PEROXIDOS">REPORTE-Peróxidos</option>
                <option value="OTROS">REPORTE-Otros</option>
                <option value="MMPP">REPORTE-MMPP</option>
            </select>

            <div id="producto-container" style="display: none;">
                <label for="producto">Producto:</label>
                <select id="producto" name="producto">
                    <!-- SE LLENARA DINAMICAMENTE  -->
                </select>
            </div>

            <button type="submit">Mostrar Reporte</button>
        </form>
    </div>

    <!-- Scripts al final del cuerpo del documento -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Define los productos con nombres descriptivos para cada tipo de reporte
            const productos = {
                "ALCALINOS": [
                    { value: "S10", text: "S10-CAUSTI FOAM CL" },
                    { value: "S11", text: "S11-CAUSTI LC 200" },
                    { value: "S14", text: "S14-HIPOCLORITO 13%" },
                    { value: "S20", text: "S20-SOSA LIQUIDA 50%" },
                    { value: "S24", text: "S24-LCC ALKA" }
                ],
                "ACIDOS": [
                    { value: "S02", text: "S02-CLORHIDRICO" },
                    { value: "S03", text: "S03-NITRICO" },
                    { value: "S04", text: "S04-SULFURICO" },
                    { value: "S12", text: "S12-DESINOX FN" },
                    { value: "S18", text: "S18-SANIPER 15%" },
                    { value: "S25", text: "S25-STONE NF" }
                ],
                "NEUTROS": [
                    { value: "S07", text: "S07-BETA QUAT 4" },
                    { value: "S08", text: "S08-BIO BASIC A" },
                    { value: "S09", text: "S09-BIO TECH" },
                    { value: "S13", text: "S13-GEL TROLL FOAM" },
                    { value: "S15", text: "S15-ML-100 FOAM" },
                    { value: "S29", text: "S29-SHAMPOO MANOS" },
                    { value: "S31", text: "S31-FX3" }
                ],
                "PEROXIDOS": [
                    { value: "S23", text: "S23-PEROXIDO BATH" },
                    { value: "S26", text: "S26-PER WASH" },
                    { value: "S23B", text: "S23B-PEROXIDO IPS" }
                ],
                "OTROS": [
                    { value: "S05", text: "S05-ALCOHOL ETILICO" },
                    { value: "S27", text: "S27-AGUA DESTILADA" },
                    { value: "S28", text: "S28-THINNER" },
                    { value: "S32", text: "S32-GLICOL" },
                    { value: "S33", text: "S33-GEL TROLL GEL" }
                ],
                "MMPP": [
                    { value: "S30", text: "S30-CLORHIDRICO 2N" }
                ]
            };

            const tipoSelect = document.getElementById('tipo');
            const productoContainer = document.getElementById('producto-container');
            const productoSelect = document.getElementById('producto');

            // Función para actualizar los productos según el tipo de reporte seleccionado
            tipoSelect.addEventListener('change', function() {
                const tipo = tipoSelect.value;
                productoSelect.innerHTML = ''; // Limpiar las opciones anteriores

                if (productos[tipo]) {
                    productoContainer.style.display = 'block'; // Mostrar el campo de selección de productos
                    productos[tipo].forEach(function(producto) {
                        const option = document.createElement('option');
                        option.value = producto.value;
                        option.textContent = producto.text;
                        productoSelect.appendChild(option);
                    });
                } else {
                    productoContainer.style.display = 'none'; // Ocultar el campo de selección de productos si no aplica
                }
            });

            document.getElementById('report-form').addEventListener('submit', function(event) {
                const fechaInicio = document.getElementById('fecha_inicio').value;
                const fechaFin = document.getElementById('fecha_fin').value;
                const tipo = document.getElementById('tipo').value;

                if (!fechaInicio || !fechaFin || !tipo) {
                    event.preventDefault(); // Evita el envío del formulario
                    Swal.fire({
                        title: '¡Oops!',
                        text: 'Por favor selecciona todos los campos obligatorios.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Entendido'
                    });
                }
            });
        });
    </script>
</body>
</html>
