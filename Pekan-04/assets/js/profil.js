document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.getElementById("foto_profil");
    const imagePreview = document.getElementById("profileImagePreview");

    if (!fileInput || !imagePreview) {
        return;
    }

    fileInput.addEventListener("change", function (event) {
        const file = event.target.files && event.target.files[0];

        if (!file) {
            return;
        }

        if (!file.type.startsWith("image/")) {
            return;
        }

        const imageUrl = URL.createObjectURL(file);
        imagePreview.src = imageUrl;
    });
});
