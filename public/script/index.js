// Espera a que cargue toda la ventana
window.addEventListener("load", function () {
    // Selecciona todos los elementos con la clase "btn-delete" y les añade un evento "click"
    // Para confirmar si el usuario desea eliminarlos.
    document.querySelectorAll(".btn-delete").forEach(function (element) {
        element.addEventListener("click", function (e) {
            e.preventDefault(); // Evita la redirección automática
            
            Swal.fire({
                title: "¿Estás seguro de que deseas eliminar este elemento?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = this.href; // Redirige a la URL del enlace
                }
            });
        });
    });

    // Selecciona el botón de eliminar portafolio y le añade un evento "click"
    // Para confirmar si el usuario desea eliminarlo.
    const btnEliminarPortfolio = document.querySelector(".btnEliminarPortfolio");
    if (btnEliminarPortfolio) {
        btnEliminarPortfolio.addEventListener("click", function (e) {
            e.preventDefault(); // Evita la redirección automática
            
            Swal.fire({
                title: "¿Estás seguro de que deseas eliminar tu portafolio?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = this.href; // Redirige a la URL del enlace
                }
            });
        });
    }
});

// Función para la creación de alertas con SweetAlert2
const mensaje = (texto,icono) => {
    Swal.fire({
        position: 'center',
        title: texto,
        icon: icono,
        showConfirmButton: false,
        timer: 2500
    });
}