 document.addEventListener('DOMContentLoaded', function () {

            const cameraButton = document.getElementById('cameraButton');
            const photoInput = document.getElementById('photoInput');
            const profileImage = document.getElementById('profileImage');
            const deletePhotoButton = document.getElementById('deletePhotoButton');
            const deletePhotoForm = document.getElementById('deletePhotoForm');

            if (cameraButton && photoInput) {

                cameraButton.addEventListener('click', function () {
                    photoInput.click();
                });

            }

            if (photoInput && profileImage) {

                photoInput.addEventListener('change', function (event) {

                    const file = event.target.files[0];

                    if (!file) {
                        return;
                    }

                    if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
                        alert('Solo se permiten imágenes JPG o PNG.');
                        photoInput.value = '';
                        return;
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        alert('La imagen no puede superar los 2 MB.');
                        photoInput.value = '';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function (e) {
                        profileImage.src = e.target.result;
                    };

                    reader.readAsDataURL(file);

                });

            }

            if (deletePhotoButton && deletePhotoForm) {

                deletePhotoButton.addEventListener('click', function () {
                    deletePhotoForm.submit();
                });

            }

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

        });
