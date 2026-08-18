document.addEventListener('DOMContentLoaded', () => {

    const campoCambio = document.getElementById('campoCambio');
    const valorActual = document.getElementById('valorActual');
    const valorActualVisible = document.getElementById('valorActualVisible');

    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelModalBtn');
    const backdrop = document.getElementById('modalBackdrop');
    const modal = document.getElementById('changeModal');

    const photoInput = document.getElementById('photoInput');
    const cameraButton = document.getElementById('cameraButton');
    const profileImage = document.getElementById('profileImage');

    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');

    const closeModal = () => {
        modal?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    const openModal = () => {
        modal?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    openBtn?.addEventListener('click', openModal);
    closeBtn?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', closeModal);


    // =========================
    // CERRAR MODAL CON ESCAPE
    // =========================

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal();
        }
    });


    // =========================
    // CAMPO A MODIFICAR
    // =========================

    campoCambio?.addEventListener('change', function () {

        const opcionSeleccionada =
            this.options[this.selectedIndex];

        const valor =
            opcionSeleccionada.dataset.valor || '';

        valorActual.value = valor;

        valorActualVisible.value =
            valor || 'No disponible';

    });

    cameraButton?.addEventListener('click', () => {
        photoInput?.click();
    });


    photoInput?.addEventListener('change', function () {

        const file = this.files?.[0];

        if (!file) {
            return;
        }

        if (!file.type.match(/^image\/(jpeg|png)$/)) {

            alert('Solo se permiten archivos JPG, JPEG o PNG.');

            this.value = '';

            return;
        }

        if (file.size > 2 * 1024 * 1024) {

            alert('La imagen no puede superar los 2 MB.');

            this.value = '';

            return;
        }

        const reader = new FileReader();

        reader.onload = (event) => {
            profileImage.src = event.target.result;
        };

        reader.readAsDataURL(file);

    });

    if (successMessage) {

        setTimeout(() => {
            successMessage.remove();
        }, 5000);

    }

    if (errorMessage) {

        setTimeout(() => {
            errorMessage.remove();
        }, 7000);

    }

});