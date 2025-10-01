export function initExperienceCreatePopups() {
    const popupOverlay = document.querySelector('.popup-overlay');

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

    const popupInputs = document.querySelectorAll('[data-popup-target]');

    popupInputs.forEach(input => {
        const popupId = input.getAttribute('data-popup-target');
        const popupElement = document.getElementById(popupId);
        const optionSelector = input.getAttribute('data-option-selector');
        const options = popupElement.querySelectorAll(optionSelector);

        input.addEventListener('click', () => openPopup(popupElement));

        options.forEach(option => {
            option.addEventListener('click', () => {
                input.value = option.textContent;
                closeActivePopup();
            });
        });
    });

    popupOverlay.addEventListener('click', (event) => {
        if (event.target === popupOverlay) {
            closeActivePopup();
        }
    });
}
