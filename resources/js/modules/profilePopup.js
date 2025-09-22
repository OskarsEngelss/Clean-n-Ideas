export function initProfilePopup() {
    const moreInfoButton = document.getElementById('profile-more-info-button');
    const popupOverlay = document.querySelector('.popup-overlay');
    const toggleMoreInfoOff = document.querySelector('.toggle-more-info-off');

    const togglePopupMenu = () => {
        popupOverlay.classList.toggle('show');
    }

    moreInfoButton.addEventListener('click', togglePopupMenu);
    toggleMoreInfoOff.addEventListener('click', togglePopupMenu);

    popupOverlay.addEventListener('click', (event) => {
        if (event.target === popupOverlay) {
            togglePopupMenu();
        }
    });
}