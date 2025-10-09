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
        popupElement.id == "experience-show-comments-popup" ? popupElement.style.display = 'grid' : popupElement.style.display = 'block';
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
    const experienceShowCommentsPopup = document.getElementById('experience-show-comments-popup');
    experienceShowCommentsPopup.addEventListener('click', (e) => {
        const button = e.target.closest('.experience-show-comment-toggle-reply-container-button');
        if (!button) return;

        const repliesContainer = button.nextElementSibling;
        if (!repliesContainer) return;

        const isVisible = repliesContainer.classList.toggle('show');

        if (isVisible) {
            button.textContent = 'Hide';
        } else {
            const replyCount = parseInt(button.dataset.replyCount) || 0;
            button.textContent = replyCount === 1 ? '1 Reply' : `${replyCount} Replies`;
        }
    });
}
