
document.addEventListener('DOMContentLoaded', function() {
    // Selección del enlace "Volver Atrás"
    const enlaceVolverAtras = document.querySelector('.bot-volver-atras');

    // Agregar evento click al enlace
    enlaceVolverAtras.addEventListener('click', function(event) {
        event.preventDefault(); // Prevenir el comportamiento por defecto del enlace

        // Mostrar SweetAlert
        Swal.fire({
            title: '¿Estás seguro?',
            text: '¿Realmente deseas volver atrás?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, volver atrás',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirigir al usuario a la página 'entrada.html'
                window.location.href = 'entrada.html';
            }
        });
    });
});
