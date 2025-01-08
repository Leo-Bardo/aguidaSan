<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "bdaguidasan";

// Conectar a la base de datos
$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

// Verificar la conexión
if (!$enlace) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

// Recoger los datos del formulario de manera segura
$fecha = mysqli_real_escape_string($enlace, $_POST['fecha']);
$nombrePesador = mysqli_real_escape_string($enlace, $_POST['nombrePesador']);
$nombreEntrego = mysqli_real_escape_string($enlace, $_POST['nombreEntrego']);
$area = mysqli_real_escape_string($enlace, $_POST['area']);
$nombreReceptor = mysqli_real_escape_string($enlace, $_POST['nombreReceptor']);
$turno = mysqli_real_escape_string($enlace, $_POST['turno']);
$produccionRealizada = mysqli_real_escape_string($enlace, $_POST['produccionRealizada']);
$producto = mysqli_real_escape_string($enlace, $_POST['producto']);
$cantidadSalida = mysqli_real_escape_string($enlace, $_POST['cantidadSalida']);

// Preparar la consulta SQL para insertar en la tabla `productos`
$insertarProducto = "INSERT INTO stock (producto,cantidadSalida) VALUES (?,?)";
$stmtProducto = mysqli_stmt_init($enlace);
if (mysqli_stmt_prepare($stmtProducto, $insertarProducto)) {
    mysqli_stmt_bind_param($stmtProducto, "sd", $producto,$cantidadSalida);
    if (mysqli_stmt_execute($stmtProducto)) {
        // Éxito al insertar en la tabla `productos`
        mysqli_stmt_close($stmtProducto);

        // Preparar la consulta SQL con sentencia preparada para insertar en la tabla `salida`
        $insertarSalida = "INSERT INTO salida (fecha, nombrePesador, nombreEntrego, area, nombreReceptor, turno, produccionRealizada, producto, cantidadSalida)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtSalida = mysqli_stmt_init($enlace);
        if (mysqli_stmt_prepare($stmtSalida, $insertarSalida)) {
            mysqli_stmt_bind_param($stmtSalida, "sssssssss", $fecha, $nombrePesador, $nombreEntrego, $area, $nombreReceptor, $turno, $produccionRealizada, $producto, $cantidadSalida);
            if (mysqli_stmt_execute($stmtSalida)) {
                // Éxito al insertar en la tabla `salida`
                $var = "Registro insertado correctamente.";
            } else {
                // Error al ejecutar la consulta de salida
                $var = "Error al ejecutar la consulta de salida: " . mysqli_error($enlace);
            }
            mysqli_stmt_close($stmtSalida);
        } else {
            // Error en la preparación de la consulta de salida
            $var = "Error en la preparación de la consulta de salida: " . mysqli_stmt_error($stmtSalida);
        }
    } else {
        // Error al ejecutar la consulta de productos
        $var = "Error al ejecutar la consulta de productos: " . mysqli_error($enlace);
    }
} else {
    // Error en la preparación de la consulta de productos
    $var = "Error en la preparación de la consulta de productos: " . mysqli_stmt_error($stmtProducto);
}

// Cerrar la conexión
mysqli_close($enlace);
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Título de tu página</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Sweet Alert CDN -->
    
    <style>
    /* Estilos para el botón "LISTO" */
    .contenedor-botones {
        text-align: center; /* Centra el botón horizontalmente */
        margin-top: 20px; /* Margen superior para separarlo del contenido superior */
    }

    .boton-volver-atras {
        display: inline-block;
        padding: 80px 160px; /* Ajusta el padding para el tamaño del botón */
        font-size: 3.5rem; /* Tamaño de fuente */
        font-weight: bold; /* Texto en negrita */
        text-decoration: none; /* Sin subrayado */
        color: #fff; /* Color del texto */
        background: #1a1a1c; /* Color de fondo gris */
        border-radius: 8px; /* Borde redondeado */
        transition: background-color 0.3s ease; /* Transición suave para el color de fondo */

        /* Sombra suave para el efecto de elevación */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .boton-volver-atras:hover {
        background-color: #606060; /* Cambia el color de fondo al pasar el mouse */
    }
    
</style>

</head>
<body>
    <!-- Incluir la barra de navegación -->
    <div id="navbar-container"></div>
    <!-- Contenido principal -->
    <div class="contenedor-principal">
        <!-- Contenido del formulario aquí -->
    </div>

    <!-- Botón Volver Atrás -->
    <div class="contenedor-botones">
        <a href="#" id="boton-volver-atras" class="boton-volver-atras"><span>REGISTRAR</span></a>
    </div>

    <!-- Script JavaScript para SweetAlert y redirección -->
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
