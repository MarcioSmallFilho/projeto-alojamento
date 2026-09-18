document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalLightbox');
    const imgModal = document.getElementById('imgLightbox');

    if (modal) {
        document.querySelectorAll('.areaImg img').forEach(img => {
            img.addEventListener('click', () => {
                imgModal.src = img.src;
                modal.classList.add('aberto');
            });
        });

        modal.addEventListener('click', () => {
            modal.classList.remove('aberto');
        });
    }
});