<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEUTROS</title>
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Estilos adicionales */
        :root {
            --color-background: #333;
            --color-text: #fff;
            --color-card-background: #444;
            --color-quantity: #007bff;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--color-background);
            color: var(--color-text);
            margin: 0;
            padding: 20px;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            align-items: center;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            max-width: 100%; /* Ajustar según sea necesario */
            margin-top: 20px;
        }
        .card {
            background-color: var(--color-card-background);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            padding: 30px;
            flex: 1 1 250px; /* Ajustar el ancho de las cartas según el diseño */
            text-align: center;
            margin-bottom: 20px; /* Espacio entre cada tarjeta */
            min-height: 200px; /* Altura mínima de las tarjetas */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: var(--color-text);
            margin-bottom: 10px;
        }
        .card-content {
            font-size: 16px;
            margin-bottom: 8px;
        }
        .cantidad-actual {
            font-weight: bold;
            color: var(--color-quantity);
        }
        .card .btn {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .card .btn:hover {
            background-color: #45a049;
        }
        .btn-regresar {
            display: inline-block;
            margin-top: 20px;
            margin-bottom: 20px; /* Ajuste para dar espacio al final */
        }
        .btn-regresar a {
            display: inline-block;
            background-color: #777;
            background: rgba(0, 0, 0, 0.7);
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-regresar a:hover {
            background-color: #555;
        }
        .btn-regresar img {
            vertical-align: middle;
            margin-right: 10px;
            width: 50px; /* Ajustar el tamaño de la imagen según sea necesario */
        }
    </style>
</head>
<body>
    <!-- Incluir la barra de navegación -->
    <div id="navbar-container"></div>

    <h1 style="margin-top: 20px; font-size: 3em; text-align: center; color: #666666;">INVENTARIO DE NEUTROS</h1>
    <div class="container">
        <?php
        // Configuración de la conexión a la base de datos
        $servidor = "localhost";
        $usuario = "root";
        $clave = "";
        $baseDeDatos = "bdaguidasan";

        // Conexión a la base de datos
        $enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

        // Verificar la conexión
        if (!$enlace) {
            die("Error al conectar con la base de datos: " . mysqli_connect_error());
        }

        // Array de productos con sus nombres correspondientes
        $productosNombres = array(
            "S07" => "S07-BETA QUAT 4",
            "S08" => "S08-BIO BASIC A",
            "S09" => "S09-BIO TECH",
            "S13" => "S13-GEL TROLL FOAM",
            "S15" => "S15-ML-100FOAM",
            "S29" => "S29-SHAMPOO MANOS",
            "S31" => "S31-FX3",
            // Agregar más productos según sea necesario
        );

        // Array de productos y sus colores específicos
        $colores = array(
            // Grupo de productos: NEUTROS -->
            "S07" => "#40E0D0",
            "S08" =>  "#40E0D0",
            "S09" =>  "#40E0D0",
            "S13" =>  "#40E0D0",
            "S15" =>  "#40E0D0",
            "S29" =>  "#40E0D0",
            "S31" =>  "#40E0D0",
            // Puedes agregar más productos y colores según necesites
        );

        // Obtener los productos que están definidos en el array de colores
        $productos = "'" . implode("', '", array_keys($colores)) . "'";

        // Consulta para obtener el inventario actual de productos ordenado por color
        $query = "SELECT producto, SUM(cantidadEntrada) AS totalEntrada, SUM(cantidadSalida) AS totalSalida
                  FROM stock 
                  WHERE producto IN ($productos)
                  GROUP BY producto";

        // Ejecutar la consulta
        $resultado = mysqli_query($enlace, $query);

        // Verificar si hay resultados
        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $codigoProducto = htmlspecialchars($fila['producto']);
                $nombreProducto = isset($productosNombres[$codigoProducto]) ? $productosNombres[$codigoProducto] : "Producto Desconocido";
                $totalEntrada = $fila['totalEntrada'];
                $totalSalida = $fila['totalSalida'];
                $cantidadActual = $totalEntrada - $totalSalida;
                $color = isset($colores[$codigoProducto]) ? $colores[$codigoProducto] : "#444"; // Color por defecto si no hay color definido
                
                // Determinar si mostrar el punto de alerta
                $alerta = '';
                if ($cantidadActual < 10) {
                    $alerta = 'card-alert';
                }
                
                // Mostrar tarjeta de producto con el color específico
                echo "<div class='card' style='background-color: $color;'>";
                echo "<div class='card-title'>$nombreProducto</div>";
                echo "<div class='card-content'>INV.INICIAL: $totalEntrada</div>";
                echo "<div class='card-content'>INV.ACTUAL: <span class='cantidad-actual'>$cantidadActual</span></div>";
                echo "<a href='tabla_salida.php?producto=" . urlencode($codigoProducto) . "' class='btn'>SALIDAS</a>";
                echo "<br>";
                echo "<a href='tabla_entrada.php?producto=" . urlencode($codigoProducto) . "' class='btn'>ENTRADAS</a>";
                echo "<div class='$alerta'></div>"; // Punto de alerta
                echo "</div>"; // Cierre de la tarjeta
            }
        } else {
            echo "No se encontraron registros de inventario para los productos definidos.";
        }

        // Cerrar la conexión
        mysqli_close($enlace);
        ?>
    </div> <!-- Cierre del contenedor -->

    <!-- Botón de regresar con imagen -->
    <div class="btn-regresar">
        <a href="materiales.html">
            <img src="imagenes/regresar.png" alt="Regresar">
        </a>
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
