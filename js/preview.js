// preview.js
const fileInput = document.querySelector('#torokupic');
fileInput.addEventListener('change', (event) => {
    const [file] = event.target.files;
    const figureImage = document.querySelector('#figureImage');
    const figure = document.querySelector('#figure');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            figureImage.setAttribute('src', e.target.result);
            figure.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        figure.style.display = 'none';
    }
});
