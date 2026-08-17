function closeErrorModal() {
                const modal = document.getElementById('errorModal');

                if (modal) {
                    modal.classList.add('hidden');
                }
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeErrorModal();
                }
            });

            const errorModal = document.getElementById('errorModal');

            if (errorModal) {
                errorModal.addEventListener('click', function (event) {
                    if (event.target === this) {
                        closeErrorModal();
                    }
                });
            }