export function initSettings() {
    const profilePictureInput = document.getElementById('settings-profile-picture-input');
    const profilePicturePreview = document.getElementById('settings-profile-picture');
    const profilePicturePreviewSvg = document.getElementById('settings-profile-picture-svg');

    function profilePictureClick() {
        profilePictureInput.click();
    }

    profilePicturePreview.addEventListener('click', profilePictureClick);
    profilePicturePreviewSvg.addEventListener('click', profilePictureClick);

    profilePictureInput.addEventListener('change', (event) => {
        const [file] = event.target.files;
        if (file) {
            profilePicturePreview.src = URL.createObjectURL(file);
        }
    });
}