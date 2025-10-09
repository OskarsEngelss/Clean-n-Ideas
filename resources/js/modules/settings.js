export function initSettings() {
    const profilePictureInput = document.getElementById('settings-profile-picture-input');
    const profilePicturePreview = document.getElementById('settings-profile-picture');
    const originalProfilePictureSrc = profilePicturePreview.src;
    const profilePicturePreviewSvg = document.getElementById('settings-profile-picture-svg');

    //Const for showing cancel and save buttons
    let formChanged = false;
    const settingsForm = document.getElementById('settings-form');
    const settingsProfileDeleteFakeButtonContainer = document.querySelector('.settings-delete-profile-fake-button-container');
    const settingsSaveCancelButtonContainer = document.querySelector('.settings-save-cancel-button-container');

    //Const cropper stuff
    const cropperPopup = document.getElementById('settings-profile-picture-cropper-popup');
    const cropperImage = document.getElementById('cropper-image');
    const cropButton = document.getElementById('crop-button');
    const cropBox = document.getElementById('crop-box');
    const croppedFileInput = document.getElementById('cropped-image-input');

    function profilePictureClick() {
        profilePictureInput.value = '';
        profilePictureInput.click();
    }

    profilePicturePreview.addEventListener('click', profilePictureClick);
    profilePicturePreviewSvg.addEventListener('click', profilePictureClick);


    profilePictureInput.addEventListener('change', (event) => {
        const [file] = event.target.files;
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            cropperImage.onload = () => {
                const imgW = cropperImage.clientWidth;
                const imgH = cropperImage.clientHeight;
                const size = Math.floor(Math.min(imgW, imgH) * 0.8);

                cropBox.style.width = `${size}px`;
                cropBox.style.height = `${size}px`;
                cropBox.style.left = `${(imgW - size) / 2}px`;
                cropBox.style.top = `${(imgH - size) / 2}px`;
            };

            cropperImage.src = e.target.result;
            openPopup(cropperPopup);
        };
        reader.readAsDataURL(file);
    });

    //Crop logic
    let isDragging = false;
    let startX, startY;

    cropBox.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.offsetX;
        startY = e.offsetY;
    });

    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;

        const rect = cropperImage.getBoundingClientRect();
        let left = e.clientX - rect.left - startX;
        let top = e.clientY - rect.top - startY;

        left = Math.max(0, Math.min(left, cropperImage.clientWidth - cropBox.offsetWidth));
        top = Math.max(0, Math.min(top, cropperImage.clientHeight - cropBox.offsetHeight));

        cropBox.style.left = `${left}px`;
        cropBox.style.top = `${top}px`;
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
    });

    let isResizing = false;
    let resizeStartX, resizeStartY, startSize, startLeft, startTop;
    let currentHandle = null;

    const handles = cropBox.querySelectorAll('.resize-handle');

    handles.forEach(handle => {
        handle.addEventListener('mousedown', (e) => {
            e.stopPropagation();
            isResizing = true;
            currentHandle = handle.classList[1];
            resizeStartX = e.clientX;
            resizeStartY = e.clientY;
            startSize = cropBox.offsetWidth;
            startLeft = parseFloat(cropBox.style.left);
            startTop = parseFloat(cropBox.style.top);
        });
    });

    document.addEventListener('mousemove', (e) => {
        if (!isResizing) return;

        const imgRect = cropperImage.getBoundingClientRect();

        const dx = e.clientX - resizeStartX;
        const dy = e.clientY - resizeStartY;
        let delta = Math.max(dx, dy);

        let newSize;
        let newLeft = startLeft;
        let newTop = startTop;

        switch (currentHandle) {
            case 'top-left':
                delta = Math.min(delta, startLeft, startTop);
                newSize = startSize - delta;
                newLeft = startLeft + delta;
                newTop = startTop + delta;
                break;
            case 'top-right':
                delta = Math.min(dx, startTop);
                newSize = startSize + delta;
                newTop = startTop - delta;
                break;
            case 'bottom-left':
                delta = Math.min(dy, startLeft);
                newSize = startSize + delta;
                newLeft = startLeft - delta;
                break;
            case 'bottom-right':
                delta = Math.min(delta, imgRect.width - startLeft, imgRect.height - startTop);
                newSize = startSize + delta;
                break;
        }

        const minSize = 50;
        const maxSize = Math.min(
            imgRect.width - newLeft,
            imgRect.height - newTop
        );

        newSize = Math.max(minSize, Math.min(newSize, maxSize));

        cropBox.style.width = `${newSize}px`;
        cropBox.style.height = `${newSize}px`;
        cropBox.style.left = `${newLeft}px`;
        cropBox.style.top = `${newTop}px`;
    });

    document.addEventListener('mouseup', () => {
        isResizing = false;
        currentHandle = null;
    });

    cropButton.addEventListener('click', () => {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        const imgRect = cropperImage.getBoundingClientRect();
        const boxRect = cropBox.getBoundingClientRect();

        const scaleX = cropperImage.naturalWidth / cropperImage.width;
        const scaleY = cropperImage.naturalHeight / cropperImage.height;

        const sx = (boxRect.left - imgRect.left) * scaleX;
        const sy = (boxRect.top - imgRect.top) * scaleY;
        const sw = cropBox.offsetWidth * scaleX;
        const sh = cropBox.offsetHeight * scaleY;

        canvas.width = sw;
        canvas.height = sh;

        ctx.drawImage(cropperImage, sx, sy, sw, sh, 0, 0, sw, sh);

        canvas.toBlob((blob) => {
            const file = new File([blob], 'cropped.png', { type: 'image/png' });
            const dt = new DataTransfer();
            dt.items.add(file);
            croppedFileInput.files = dt.files;

            profilePicturePreview.src = URL.createObjectURL(blob);

            settingsSaveCancelButtonContainer.classList.add('show');
            settingsProfileDeleteFakeButtonContainer.classList.add('hide');
            formChanged = true;

            closeActivePopup();
        }, 'image/png');
    });




    const settingsInput = document.querySelectorAll('.settings-input');
    settingsInput.forEach(input => {
        input.addEventListener('input', showSaveCancelButtons);
    });

    function showSaveCancelButtons() {
        if (!formChanged) {
            settingsSaveCancelButtonContainer.classList.add('show');
            settingsProfileDeleteFakeButtonContainer.classList.add('hide');
            formChanged = true;
        }
    }

    const settingsCancelButton = document.getElementById('settings-cancel-button');
    settingsCancelButton.addEventListener('click', () => {
        settingsForm.reset();
        profilePicturePreview.src = originalProfilePictureSrc;
        settingsSaveCancelButtonContainer.classList.remove('show');
        settingsProfileDeleteFakeButtonContainer.classList.remove('hide');
        formChanged = false;
    });


    //Settings popup logic (very similar to experience show)
    const popupOverlay = document.querySelector('.popup-overlay');
    const offButton = document.querySelectorAll('.settings-popups-off-button');

    offButton.forEach(button => {
        button.addEventListener('click', () => closeActivePopup());
    });

    let activePopup = null;

    function closeActivePopup() {
        if (activePopup) {
            activePopup.popup.style.display = 'none';
            popupOverlay.classList.remove('show');
            activePopup = null;
        }
    }

    function openPopup(popupElement) {
        closeActivePopup();
        popupElement.style.display = 'block';
        popupOverlay.classList.add('show');
        activePopup = { popup: popupElement };
    }

    const popupButtons = document.querySelectorAll('[data-popup-target]');

    popupButtons.forEach(button => {
        const popupId = button.getAttribute('data-popup-target');
        const popupElement = document.getElementById(popupId);

        button.addEventListener('click', () => openPopup(popupElement));
    });


    // let mouseDownStartedInsidePopup = false;
    // popupOverlay.addEventListener('mousedown', (e) => {
    //     // Check if click started *inside* the popup, not the overlay
    //     mouseDownStartedInsidePopup = e.target !== popupOverlay;
    // });
    // popupOverlay.addEventListener('click', (event) => {
    //     if (!mouseDownStartedInsidePopup || e.target === popupOverlay) {
    //         closeActivePopup();
    //     }

    //     // Reset tracker
    //     mouseDownStartedInsidePopup = false;
    // });
}