    const photoInput = document.getElementById('photoInput');
    const cameraButton = document.getElementById('cameraButton');
    const profileImage = document.getElementById('profileImage');


    // Abrir selector
    cameraButton.addEventListener('click', () => {
        photoInput.click();
    });


    // Preview
    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) {
            return;
        }
        const allowedTypes = [
            'image/jpeg',
            'image/png'
        ];

        if (!allowedTypes.includes(file.type)) {
            alert('Solo se permiten imágenes JPG o PNG.');
            this.value = '';
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('La imagen no puede superar los 2 MB.');
            this.value = '';
            return;
        }
        profileImage.src = URL.createObjectURL(file);
    });