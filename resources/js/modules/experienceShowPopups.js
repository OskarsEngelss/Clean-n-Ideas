export function initExperienceShowPopups() {
    const popupOverlay = document.querySelector('.popup-overlay');
    const offButton = document.querySelectorAll('.experience-show-popups-off-button');

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

    popupOverlay.addEventListener('click', (event) => {
        if (event.target === popupOverlay) {
            closeActivePopup();
        }
    });

    //Toggle reply visiblity code
    const showRepliesButtons = document.querySelectorAll('.experience-show-comment-toggle-reply-container-button');
    showRepliesButtons.forEach(button => {
        button.addEventListener('click', () => {
            const repliesContainer = button.nextElementSibling;
            const isVisible = repliesContainer.classList.toggle('show');

            if (isVisible) {
                button.textContent = 'Hide';
            } else {
                button.textContent = button.getAttribute('data-reply-label');
            }
        });
    });
}
