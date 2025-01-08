<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles/loginStyle.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="src/sweetalert2.min.css">
</head>
<body>
    <div class="formulario">
        <h1><i class="fas fa-user-circle"></i> INICIA  SESIÓN</h1>
        <form id="loginForm" method="post" action="login/procesarSesion.php">
            <div class="input-container">
                <input type="number" id="codEmpleado" placeholder="Código de Empleado" name="codEmpleado" pattern="\d+">
                <label for="codEmpleado"><i class="fa-solid fa-id-card"></i></label>
                <span class="border"></span>
            </div>
            <div class="input-container">
                <input type="password" id="password" placeholder="Contraseña" name="password">
                <label for="password"><i class="fas fa-lock"></i></label>
                <span class="border"></span>
            </div>
            <a href="#" id="forgotPasswordLink">¿Olvidaste tu contraseña?</a>
            <button type="submit">INGRESAR <i class="fas fa-sign-in-alt"></i></button>
        </form>
        <p>¿No tienes una cuenta? <a href="registroo.php">Regístrate</a></p>
    </div>
    <script src="src/sweetalert2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(event) {
                event.preventDefault(); // Previene el envío del formulario
                
                // Verifica si los campos están vacíos
                if ($('#username').val() === '' || $('#codEmpleado').val() === '' || $('#password').val() === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor, complete todos los campos.'
                    });
                    return;
                }

                // Realiza una solicitud AJAX para validar el inicio de sesión
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'error') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        } else if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: 'Inicio de sesión exitoso.'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'menu.php'; // Ajusta según sea necesario
                                }
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema con el servidor. Intente de nuevo más tarde.'
                        });
                    }
                });
            });

            $('#forgotPasswordLink').on('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Recuperar Contraseña',
                    input: 'number',
                    inputLabel: 'Introduce tu código de empleado',
                    inputPlaceholder: 'Código de empleado',
                    showCancelButton: true,
                    confirmButtonText: 'Enviar',
                    inputAttributes: {
                        required: true // Hace que el campo sea obligatorio
                    },
                    preConfirm: (codEmpleado) => {
                        // Verifica si se ha introducido un código de empleado
                        if (!codEmpleado) {
                            Swal.showValidationMessage('ERROR.');
                            return false; // Previene la continuación del proceso
                        }

                        return $.ajax({
                            url: 'recuperar_contrasena.php',
                            type: 'POST',
                            dataType: 'json',
                            data: { codEmpleado: codEmpleado }
                        }).then(response => {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Código de Recuperación',
                                    html: `Código: <input type="text" id="codigo" value="${response.codigo}" readonly>`,
                                    showCancelButton: true,
                                    confirmButtonText: 'Ingresar Código',
                                    preConfirm: () => {
                                        const codigo = $('#codigo').val();
                                        const form = $('<form>', {
                                            'action': 'verificar_codigo.php',
                                            'method': 'post'
                                        }).append(
                                            $('<input>', { 'type': 'hidden', 'name': 'codigo', 'value': response.codigo }),
                                            $('<input>', { 'type': 'hidden', 'name': 'codEmpleado', 'value': response.codEmpleado }),
                                            $('<input>', { 'type': 'hidden', 'name': 'codigo_ingresado', 'value': codigo })
                                        );

                                        return $.ajax({
                                            url: form.attr('action'),
                                            type: 'POST',
                                            dataType: 'json',
                                            data: form.serialize()
                                        }).then(response => {
                                            if (response.status === 'success') {
                                                Swal.fire({
                                                    title: 'Éxito',
                                                    text: response.message,
                                                    icon: 'success',
                                                    confirmButtonText: 'Actualizar Contraseña'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        Swal.fire({
                                                            title: 'Actualizar Contraseña',
                                                            input: 'password',
                                                            inputLabel: 'Introduce tu nueva contraseña',
                                                            inputPlaceholder: 'Nueva Contraseña',
                                                            inputAttributes: {
                                                                autocapitalize: 'off'
                                                            },
                                                            showCancelButton: true,
                                                            confirmButtonText: 'Actualizar',
                                                            showLoaderOnConfirm: true,
                                                            preConfirm: (nuevaContrasena) => {
                                                                return $.ajax({
                                                                    url: 'actualizar_contrasena.php',
                                                                    type: 'POST',
                                                                    dataType: 'json',
                                                                    data: {
                                                                        codEmpleado: response.codEmpleado,
                                                                        nueva_contrasena: nuevaContrasena
                                                                    }
                                                                }).then(response => {
                                                                    if (response.status === 'success') {
                                                                        Swal.fire('Éxito', response.message, 'success');
                                                                    } else {
                                                                        Swal.fire('Error', response.message, 'error');
                                                                    }
                                                                }).catch(error => {
                                                                    Swal.fire('Error', 'Error en la solicitud: ' + JSON.stringify(error), 'error');
                                                                });
                                                            }
                                                        });
                                                    }
                                                });
                                            } else {
                                                Swal.fire('Error', response.message, 'error');
                                            }
                                        }).catch(error => {
                                            Swal.fire('Error', 'Error en la solicitud: ' + JSON.stringify(error), 'error');
                                        });
                                    }
                                });
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        }).catch(error => {
                            Swal.fire('Error', 'Error en la solicitud: ' + JSON.stringify(error), 'error');
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                });
            });
        });
    </script>
</body>
</html>
