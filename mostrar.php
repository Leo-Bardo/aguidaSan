<?php 
include('login/conexion.php');

$consulta = "SELECT * FROM salida";
$resultado = mysqli_query($conex, $consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Salidas</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .tabla-container {
        width: 90%; /* Ajusta el ancho de la tabla según sea necesario */
        margin-top: 20px; /* Añade margen superior */
        margin-left: 9%; /* Ajusta el margen izquierdo para mover la tabla hacia la derecha */
        overflow-x: auto; /* Añade scroll horizontal si es necesario */
    }

    .styled-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px; /* Añade margen inferior para separar del pie de página */
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
        background-color: #f2f2f2; /* Color de fondo del encabezado pegajoso */
    }

    .styled-table tbody {
        background-color: #fff; /* Color de fondo del cuerpo de la tabla */
    }
    .sidebar {
            
            z-index: 1; /* Asegura que la barra lateral esté sobre el contenido */
        }
</style>

</head>
<body>


    <div class="tabla-container">
        <table class="styled-table">
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
                    <th>Unidad</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $resultado->fetch_array()) { ?>
                <tr>
                    <td><?php echo $row['idSalida']; ?></td>
                    <td><?php echo $row['fecha']; ?></td>
                    <td><?php echo $row['nombrePesador']; ?></td>
                    <td><?php echo $row['nombreEntrego']; ?></td>
                    <td><?php echo $row['area']; ?></td>
                    <td><?php echo $row['nombreReceptor']; ?></td>
                    <td><?php echo $row['turno']; ?></td>
                    <td><?php echo $row['produccionRealizada']; ?></td>
                    <td><?php echo $row['producto']; ?></td>
                    <td><?php echo $row['cantidadSalida']; ?></td>
                    <td><?php echo $row['unidad']; ?></td>
                </tr>
            <?php } ?>
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
    </script>
</body>
</html>
