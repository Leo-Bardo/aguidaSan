<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MENU</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('img/gris.jpg'); /* Cambiar 'ruta/a/tu/imagen.jpg' por la URL de tu imagen */
            background-size: cover; /* Para cubrir toda la pantalla */
            background-position: center; /* Posición centrada */
            background-repeat: no-repeat; /* Evitar que se repita la imagen */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .contenido {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.384); /* Color de fondo blanco con 80% de opacidad */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 80%;
            width: 100%;
            transition: transform 0.3s ease, opacity 0.6s ease; /* Transición añadida */
            opacity: 0; /* Inicialmente oculto */
            transform: scale(0.9); /* Escala inicial */
        }
        
        .contenido.visible {
            opacity: 1; /* Mostrar con opacidad completa */
            transform: scale(1); /* Escala normal */
        }
        
        h2 {
            color: #666666;
            font-size: 2.5em; /* Aumentamos el tamaño del título */
            margin-bottom: 20px; /* Añadimos espacio abajo del título */
        }
        
        p {
            font-size: 1.3em; /* Aumentamos el tamaño del texto */
            color: #333;
            line-height: 1.8;
            margin-bottom: 20px; /* Añadimos espacio abajo del párrafo */
        }
    
        /* Media Query para tablets */
        @media (max-width: 768px) {
            .contenido {
                padding: 30px; /* Ajustamos el padding para tablets */
            }
            h2 {
                font-size: 2em; /* Reducimos un poco el tamaño del título en tablets */
            }
            p {
                font-size: 1.2em; /* Reducimos un poco el tamaño del texto en tablets */
            }
        }
    </style>
</head>
<body>
    <!-- Incluir la barra de navegación -->
    <div id="navbar-container"></div>
    <!-- Aquí va el contenido de tu página -->
    <div class="contenido">
        <h2>¡BIENVENIDO AL SISTEMA DE CONTROL DE SANITIZANTES!</h2>
        <div id="welcome-message"></div> <!-- Aquí se mostrará el mensaje de bienvenida -->
        <p>Este es un sistema seguro y eficiente para registrar los sanitizantes</p>
    </div>
    <!-- Scripts al final del cuerpo del documento -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar la barra de navegación desde un archivo externo
            fetch('navBar.html')
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
                                window.location.href = 'index.html';
                            }
                        });
                    });

                    // Mostrar el contenido con transición una vez que todo esté cargado
                    const contenido = document.querySelector('.contenido');
                    contenido.classList.add('visible');
                })
                .catch(error => console.error('Error al cargar la barra de navegación:', error));

            // Obtener el nombre de usuario desde sessionStorage o localStorage
            var username = sessionStorage.getItem('username'); // O localStorage

            // Mostrar el mensaje de bienvenida con el nombre de usuario
            var welcomeMessage = document.getElementById('welcome-message');
            if (username) {
                welcomeMessage.innerHTML = `<p>Bienvenido, ${username}.</p>`;
            } else {
                welcomeMessage.innerHTML = `<p>Bienvenido.</p>`;
            }
        });
    </script>
</body>
</html>
