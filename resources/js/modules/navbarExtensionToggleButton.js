//Eksportē metodi, kura sator navigācijas joslas pagarinājuma "slēdzi"
export function initNavbarExtensionToggleButton() {
    //Iegūst pareizos elementus izmantojot id identifikatoru
    const navbarExtension = document.getElementById('navbar-extension');
    const navbarExtensionToggleButton = document.getElementById('profile-icon');

    //Pogai, šajā piemērā tā ir īstenībā bilde, iedod notikumu klausītāju
    navbarExtensionToggleButton.addEventListener('click', () => {
        //Navigācijas josla tiek parādīta un paslēpta ar "show-right" klases palīdzību
        navbarExtension.classList.toggle('show-right');
    });
}
