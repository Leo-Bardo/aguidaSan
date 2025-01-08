<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="estilos/registroo.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="src/sweetalert2.min.css">
</head>
<body>
    <div class="formulario">
        <h1><i class="fas fa-user-plus"></i>Registro de Usuario</h1>
        <form id="registroForm" method="post" action="procesarRegistro.php">
            <div class="form-row">
                <div class="input-container">
                    <input type="text" id="nombreUsuario" placeholder="Nombre" name="nombreUsuario">
                    <label for="nombreUsuario"><i class="fa-solid fa-user"></i></label>
                    <span class="border"></span>
                </div>
                <div class="input-container">
                    <input type="date" id="fechaRegistro" placeholder="Fecha de Registro" name="fechaRegistro">
                    <label for="fechaRegistro"><i class="fa-solid fa-calendar"></i></label>
                    <span class="border"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="input-container">
                    <input type="text" id="apePat" placeholder="Apellido Paterno" name="apePat" pattern="[A-Za-z\s]+">
                    <label for="apePat"><i class="fa-solid fa-user"></i></label>
                    <span class="border"></span>
                </div>
                <div class="input-container">
                    <input type="email" id="email" placeholder="Email" name="email">
                    <label for="email"><i class="fa-solid fa-envelope"></i></label>
                    <span class="border"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="input-container">
                    <input type="text" id="apeMat" placeholder="Apellido Materno" name="apeMat" pattern="[A-Za-z\s]+">
                    <label for="apeMat"><i class="fa-solid fa-user"></i></label>
                    <span class="border"></span>
                </div>
                <div class="input-container">
                    <input type="number" id="telefono" placeholder="Telefono" name="telefono" pattern="\d+">
                    <label for="telefono"><i class="fa-solid fa-phone"></i></label>
                    <span class="border"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="input-container">
                    <input type="number" id="codEmpleado" placeholder="Codigo de Empleado" name="codEmpleado">
                    <label for="codEmpleado"><i class="fa-solid fa-id-card"></i></label>
                    <span class="border"></span>
                </div>
                <div class="input-container">
                    <input type="number" id="estado" placeholder="Estado" name="estado">
                    <label for="estado"><i class="fa-solid fa-flag"></i></label>
                    <span class="border"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="input-container">
                    <input type="number" id="rol" placeholder="Rol" name="rol">
                    <label for="rol"><i class="fa-solid fa-user-tag"></i></label>
                    <span class="border"></span>
                </div>
                <div class="input-container">
                    <input type="text" id="municipio" placeholder="Municipio" name="municipio">
                    <label for="municipio"><i class="fa-solid fa-city"></i></label>
                    <span class="border"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="input-container">
                    <input type="number" id="status" placeholder="Status" name="status">
                    <label for="status"><i class="fa-solid fa-check-circle"></i></label>
                    <span class="border"></span>
                </div>
                <div class="input-container">
                    <input type="text" id="direccion" placeholder="Direccion" name="direccion">
                    <label for="direccion"><i class="fa-solid fa-map-marker-alt"></i></label>
                    <span class="border"></span>
                </div>
            </div>
            <div class="form-row">
                <div class="input-container">
                    <input type="password" id="password" placeholder="Contraseña" name="password">
                    <label for="password"><i class="fas fa-lock"></i></label>
                    <span class="border"></span>
                </div>
                <div class="input-container">
                    <input type="text" id="codigoPostal" placeholder="Codigo Postal" name="codigoPostal" pattern="\d+">
                    <label for="codigoPostal"><i class="fa-solid fa-mail-bulk"></i></label>
                    <span class="border"></span>
                </div>
            </div>
            <button type="submit">ENVIAR <i class="fas fa-paper-plane"></i></button>
        </form>
        <p>¿Ya tienes una cuenta? <a href="index.php">Inicia sesión</a></p>
    </div>
    <script src="src/sweetalert2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#registroForm').on('submit', function(e) {
                e.preventDefault(); // Prevenir el envío del formulario por defecto
                
                // Verificar si todos los campos están llenos
                let allFilled = true;
                $('#registroForm input').each(function() {
                    if ($(this).val().trim() === '') {
                        allFilled = false;
                        return false; // Salir del bucle
                    }
                });

                if (!allFilled) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor, llena todos los campos requeridos.'
                    });
                    return;
                }

                // Confirmar envío
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Deseas enviar el formulario?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, enviar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Enviar formulario si se confirma
                        $.ajax({
                            url: $(this).attr('action'),
                            type: 'POST',
                            data: $(this).serialize(),
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Éxito',
                                        text: 'Registro enviado exitosamente.'
                                    });
                                    $('#registroForm')[0].reset(); // Opcional: Reinicia el formulario después del éxito
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message || 'Hubo un error al enviar el formulario.'
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Hubo un error en la conexión con el servidor.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
