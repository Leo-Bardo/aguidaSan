// Contenido de java/script.js
document.getElementById('registroForm').addEventListener('submit', function(event) {
    event.preventDefault(); 

    let formIsValid = true;
    const inputs = document.querySelectorAll('#registroForm input, #registroForm select');

    inputs.forEach(input => {
        if (input.required && !input.value.trim()) {
            formIsValid = false;
        }
    });

    if (formIsValid) {
        Swal.fire({
            title: "¿Quieres guardar los cambios?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Guardar",
            denyButtonText: `No guardar`
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire("¡Guardado!", "", "success");
                document.getElementById('registroForm').submit(); // Envía el formulario
            } else if (result.isDenied) {
                Swal.fire("Cambios no guardados", "", "info");
            }
        });
    } else {
        Swal.fire("Por favor completa todos los campos requeridos", "", "warning");
    }
});
