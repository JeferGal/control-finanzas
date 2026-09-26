document.addEventListener("DOMContentLoaded", function () {

    // Confirmación antes de cerrar sesión
    const logoutLinks = document.querySelectorAll(".logout");

    logoutLinks.forEach(function (link) {

        link.addEventListener("click", function (event) {

            const confirmar = confirm(
                "¿Estás seguro de que deseas cerrar sesión?"
            );

            if (!confirmar) {
                event.preventDefault();
            }

        });

    });


    // Ocultar mensajes automáticamente
    const mensajes = document.querySelectorAll(".mensaje");

    mensajes.forEach(function (mensaje) {

        setTimeout(function () {

            mensaje.style.opacity = "0";

            setTimeout(function () {
                mensaje.style.display = "none";
            }, 500);

        }, 4000);

    });

});