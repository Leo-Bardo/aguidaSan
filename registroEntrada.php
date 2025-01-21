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
$nombreProveedor = mysqli_real_escape_string($enlace, $_POST['nombreProveedor']);
$nombreReceptor = mysqli_real_escape_string($enlace, $_POST['nombreReceptor']);
$area = mysqli_real_escape_string($enlace, $_POST['area']);
$turno = mysqli_real_escape_string($enlace, $_POST['turno']);
$produccionRealizada = mysqli_real_escape_string($enlace, $_POST['produccionRealizada']);
$producto = mysqli_real_escape_string($enlace, $_POST['producto']);
$cantidadEntrada = mysqli_real_escape_string($enlace, $_POST['cantidadEntrada']);

// Preparar la consulta SQL para insertar en la tabla `stock`
$insertarProducto = "INSERT INTO stock (producto,cantidadEntrada) VALUES (?,?)";
$stmtProducto = mysqli_stmt_init($enlace);
if (mysqli_stmt_prepare($stmtProducto, $insertarProducto)) {
    mysqli_stmt_bind_param($stmtProducto, "sd", $producto,$cantidadEntrada);
    if (mysqli_stmt_execute($stmtProducto)) {
        mysqli_stmt_close($stmtProducto); // Cerrar $stmtProducto después de la ejecución exitosa

        // Preparar la consulta SQL con sentencia preparada para la tabla `entrada`
        $insertarDatos = "INSERT INTO entrada (fecha, nombreProveedor, nombreReceptor, area, turno, produccionRealizada, producto, cantidadEntrada) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($enlace);
        if (mysqli_stmt_prepare($stmt, $insertarDatos)) {
            mysqli_stmt_bind_param($stmt, "ssssssss", $fecha, $nombreProveedor, $nombreReceptor, $area, $turno, $produccionRealizada, $producto, $cantidadEntrada);
            if (mysqli_stmt_execute($stmt)) {
                $var = "Registro insertado correctamente.";
            } else {
                $var = "Error al ejecutar la consulta: " . mysqli_error($enlace);
            }
            mysqli_stmt_close($stmt); // Cerrar $stmt después de la ejecución exitosa de la segunda consulta
        } else {
            $var = "Error en la preparación de la consulta: " . mysqli_stmt_error($stmt);
        }
    } else {
        $var = "Error al ejecutar la consulta de productos: " . mysqli_error($enlace);
    }
} else {
    $var = "Error en la preparación de la consulta de productos: " . mysqli_stmt_error($stmtProducto);
}

// Cerrar la conexión
mysqli_close($enlace);

echo $var; 
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Título de tu página</title>
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
                                // redirigir al usuario a la página de inicio de sesión
                                window.location.href = 'index.php';
                            }
                        });
                    });
                })
                .catch(error => console.error('Error al cargar la barra de navegación:', error));
        });
    </script>
</body>
</html>
