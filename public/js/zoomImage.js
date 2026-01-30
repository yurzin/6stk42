document.addEventListener('DOMContentLoaded', function() {
    const zoomingImg = (img) => {
        const modal = document.createElement('div');
        modal.classList.add('modalHomeGallery');

        // Создаем увеличенное изображение
        const zoomedImg = document.createElement('img');
        zoomedImg.src = img.src;
        zoomedImg.style.cssText = `
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        `;

        modal.appendChild(zoomedImg);

        document.body.appendChild(modal);
        modal.addEventListener('click', () => {
            document.body.removeChild(modal);
        });
    }
    const homeImagesGallery = document.querySelectorAll('.home-image-gallery img');
    homeImagesGallery.forEach((img) => img.addEventListener('click',  () => {
        zoomingImg(img);
    }))
});