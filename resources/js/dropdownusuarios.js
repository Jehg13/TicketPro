console.log('🔥 DROPDOWN USUARIOS CARGADO');

document.addEventListener('DOMContentLoaded', function () {

    console.log('🔥 DOM CARGADO');

    const profileButton = document.getElementById('profile-button');
    const profileDropdown = document.getElementById('profile-dropdown');
    const profileArrow = document.getElementById('profile-arrow');

    console.log('BOTON:', profileButton);
    console.log('DROPDOWN:', profileDropdown);
    console.log('FLECHA:', profileArrow);

    profileButton.addEventListener('click', function (event) {

        console.log('🔥 CLICK EN PERFIL');

        event.stopPropagation();

        profileDropdown.classList.toggle('hidden');
        profileArrow.classList.toggle('rotate-180');
    });

    document.addEventListener('click', function (event) {

        if (
            !profileButton.contains(event.target) &&
            !profileDropdown.contains(event.target)
        ) {
            profileDropdown.classList.add('hidden');
            profileArrow.classList.remove('rotate-180');
        }
    });

    profileDropdown.addEventListener('click', function (event) {
        event.stopPropagation();
    });

});