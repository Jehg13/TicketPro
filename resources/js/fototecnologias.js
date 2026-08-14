 document.getElementById('avatar-input').addEventListener('change', function(e) {

            const file = e.target.files[0];

            if (file) {

                const reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }

                reader.readAsDataURL(file);
            }

        });