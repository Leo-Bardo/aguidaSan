<?php
// Archivo: tabla_entrada.php

// Incluir archivo de conexión a la base de datos
include('login/conexion.php');

// Establecer conexión
$conex = conectar();

// Obtener el nombre del producto desde el parámetro GET
if (isset($_GET['producto'])) {
    $producto = $_GET['producto'];
} else {
    die("Producto no especificado.");
}

// Obtener fechas desde los parámetros GET (si se han proporcionado)
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Consulta base para obtener las entradas del producto específico
$consulta = "SELECT *, ROW_NUMBER() OVER (ORDER BY idEntrada) AS numeroFila 
             FROM entrada 
             WHERE producto = '$producto'";

// Aplicar filtro por fechas si se han proporcionado
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $consulta .= " AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

// Ejecutar la consulta para obtener las salidas
$resultado = mysqli_query($conex, $consulta);

// Consulta para obtener la suma total de la cantidad de entradas
$consulta_suma = "SELECT SUM(cantidadEntrada) AS totalEntradas 
                  FROM entrada 
                  WHERE producto = '$producto'";

// Aplicar el mismo filtro por fechas si se han proporcionado
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $consulta_suma .= " AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

// Ejecutar la consulta para obtener la suma total
$resultado_suma = mysqli_query($conex, $consulta_suma);
$total_entradas = mysqli_fetch_assoc($resultado_suma)['totalEntradas'];

// Estructura HTML para la tabla de entradas
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Entradas de <?php echo htmlspecialchars($producto); ?></title>
    <link rel="stylesheet" href="styles/style.css">
    <style>
        /* Estilos generales */
        body {
            background-color: #d6d4d4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .tabla-container {
            width: 90%;
            margin: 20px auto;
            overflow-x: auto;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .styled-table thead {
            position: sticky;
            top: 0;
            background-color: #f2f2f2;
        }

        .styled-table tbody {
            background-color: #fff;
        }

        /* Estilos para el contenedor de los botones */
        .botones-container {
            position: fixed;
            top: 20px; /* Ajusta la posición desde la parte superior */
            right: 20px; /* Ajusta la posición desde la derecha */
            z-index: 1000; /* Asegura que estén por encima de otros elementos si es necesario */
        }

        /* Estilos para los botones */
        .boton {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            text-align: center;
        }

        .boton:hover {
            background-color: #222;
        }
    </style>
</head>
<body>
    <!-- Contenedor de los botones en la esquina superior derecha -->
    <div class="botones-container">
        <a href="salida.html" id="btn-registrar-nuevo" class="boton">Registrar Nuevo</a>
        <a href="javascript:void(0)" id="btn-regresar" class="boton">Regresar</a>
    </div>
    <div class="tabla-container">
        <table class="styled-table">
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
                <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                    <tr>
                        <td><?php echo $row['numeroFila']; ?></td>
                        <td><?php echo $row['fecha']; ?></td>
                        <td><?php echo $row['nombreProveedor']; ?></td>
                        <td><?php echo $row['nombreReceptor']; ?></td>
                        <td><?php echo $row['area']; ?></td>
                        <td><?php echo $row['turno']; ?></td>
                        <td><?php echo $row['produccionRealizada']; ?></td>
                        <td><?php echo $row['producto']; ?></td>
                        <td><?php echo $row['cantidadEntrada']; ?></td>
                    </tr>
                <?php } ?>
                <!-- Fila para mostrar la suma total de cantidad -->
                <tr>
                    <td colspan="8"></td>
                    <td><strong>Total-Entradas:</strong></td>
                    <td><strong><?php echo $total_entradas; ?></strong></td>
                </tr>
            </tbody>
        </table>
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

        document.addEventListener('DOMContentLoaded', function() {
            // Evento click para el botón "Regresar"
            document.getElementById('btn-regresar').addEventListener('click', function(event) {
                event.preventDefault(); // Evitar que el enlace recargue la página

                Swal.fire({
                    title: '¿Regresar?',
                    text: '¿Estás seguro de que quieres regresar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, regresar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        history.back(); // Regresar atrás si el usuario confirma
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php
// Cerrar conexión a la base de datos
mysqli_close($conex);
?>
