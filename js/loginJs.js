document.getElementById('loginForm').addEventListener('submit', function(event) {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;

            if (!username || !password) {
                // Mostrar alerta si algún campo está vacío
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debes llenar todos los campos.',
                    confirmButtonColor: '#666666'
                });
                event.preventDefault(); // Evitar que el formulario se envíe
            } else {
                // Evitar que el formulario se envíe automáticamente
                event.preventDefault();
// dinamico PHP - cambiar procesamiento de datos para enlazar con la BBDD
                if (password === '1') {
                    // Contraseña correcta, almacenar el nombre de usuario y redirigir
                    sessionStorage.setItem('username', username); // O localStorage

                    // Mostrar mensaje de inicio de sesión exitoso
                    Swal.fire({
                        icon: 'success',
                        title: '¡Inicio de sesión exitoso!',
                        showConfirmButton: false,
                        timer: 500
                        
                    });

                    // Redirigir después de mostrar el mensaje
                    setTimeout(function() {
                        window.location.href = 'menu.html';
                    }, 500);
                } else {
                    // Contraseña incorrecta, mostrar mensaje de error
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: 'Contraseña incorrecta. Por favor, intenta de nuevo.',
                        confirmButtonColor: '#666666'
                    });
                    event.preventDefault(); // Evitar que el formulario se envíe
                }
            }
        });

        // Mostrar el modal de recuperación de contraseña
        var modal = document.getElementById('forgotPasswordModal');
        var btn = document.getElementById('forgotPasswordLink');
        var span = document.getElementsByClassName('close')[0];

        btn.onclick = function() {
            modal.style.display = 'block';
        }

        span.onclick = function() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        document.getElementById('forgotPasswordForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var email = document.getElementById('email').value;

            if (!email) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, introduce tu correo electrónico.',
                    confirmButtonColor: '#6666666'
                });
            } else {
                // Enviar el formulario a forgot_password.php para enviar el correo de recuperación
                fetch('forgot_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'email=' + encodeURIComponent(email)
                })
                .then(response => response.text())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Correo enviado',
                        text: 'Se ha enviado un enlace de recuperación a tu correo electrónico.',
                        confirmButtonColor: '#666666'
                        
                    });
                    modal.style.display = 'none';
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al enviar el correo. Por favor, intenta de nuevo.',
                        confirmButtonColor: '#666666'
                    });
                });
            }
        });