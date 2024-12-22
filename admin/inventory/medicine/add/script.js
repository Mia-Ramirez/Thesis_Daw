// script.js

function previewImage(event) {
    const file = event.target.files[0];
    const container = document.querySelector('.image-container');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create an image element and set the source to the file
            const img = document.createElement('img');
            img.src = e.target.result;

            // Clear the container and add the image
            container.innerHTML = ''; // Clear the content
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
}
