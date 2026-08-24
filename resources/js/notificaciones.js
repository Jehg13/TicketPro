document.addEventListener('DOMContentLoaded', () => {

    const button = document.getElementById('notif-button');
    const dropdown = document.getElementById('notif-dropdown');

    // Si los elementos no existen en esta página, no hacemos nada
    if (!button || !dropdown) {
        return;
    }

    button.addEventListener('click', (e) => {

        e.stopPropagation();

        dropdown.classList.toggle('hidden');

    });

    document.addEventListener('click', (e) => {

        if (
            !dropdown.contains(e.target) &&
            !button.contains(e.target)
        ) {
            dropdown.classList.add('hidden');
        }

    });

});