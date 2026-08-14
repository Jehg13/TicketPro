document.addEventListener('DOMContentLoaded', function () {

    const photoInput = document.getElementById('photoInput');
    const cameraButton = document.getElementById('cameraButton');
    const profileImage = document.getElementById('profileImage');


    // =====================================================
    // ABRIR SELECTOR DE IMAGEN
    // =====================================================

    if (cameraButton && photoInput) {

        cameraButton.addEventListener('click', () => {

            photoInput.click();

        });

    }


    // =====================================================
    // PREVIEW
    // =====================================================

    if (photoInput && profileImage) {

        photoInput.addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {
                return;
            }


            // FORMATOS PERMITIDOS

            const allowedTypes = [
                'image/jpeg',
                'image/png'
            ];


            if (!allowedTypes.includes(file.type)) {

                alert('Solo se permiten imágenes JPG o PNG.');

                this.value = '';

                return;
            }


            // TAMAÑO MÁXIMO: 2 MB

            if (file.size > 2 * 1024 * 1024) {

                alert('La imagen no puede superar los 2 MB.');

                this.value = '';

                return;
            }


            // MOSTRAR PREVIEW

            profileImage.src = URL.createObjectURL(file);

        });

    }

});
