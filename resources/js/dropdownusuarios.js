document.addEventListener('DOMContentLoaded', function () {

    const profileButton = document.getElementById('profile-button');
    const profileDropdown = document.getElementById('profile-dropdown');
    const profileArrow = document.getElementById('profile-arrow');

    // Si alguno de los elementos no existe en esta página,
    // no ejecutar el código.
    if (!profileButton || !profileDropdown || !profileArrow) {
        return;
    }

    // Abrir / cerrar dropdown
    profileButton.addEventListener('click', function (event) {

        event.stopPropagation();

        profileDropdown.classList.toggle('hidden');
        profileArrow.classList.toggle('rotate-180');

    });

    // Cerrar al hacer clic fuera
    document.addEventListener('click', function (event) {

        if (
            !profileButton.contains(event.target) &&
            !profileDropdown.contains(event.target)
        ) {

            profileDropdown.classList.add('hidden');
            profileArrow.classList.remove('rotate-180');

        }

    });

    // Evitar que un clic dentro del dropdown lo cierre
    profileDropdown.addEventListener('click', function (event) {

        event.stopPropagation();

    });

});