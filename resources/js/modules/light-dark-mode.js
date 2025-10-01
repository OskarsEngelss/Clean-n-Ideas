export function initLightDarkMode() {
    let darkmode = localStorage.getItem('darkmode');
    const lightDarkModeSwitch = document.getElementById('toggle-light-dark-mode');

    const enableDarkmode = () => {
        document.body.classList.add('darkmode');
        localStorage.setItem('darkmode', 'active');
    }

    const disableDarkmode = () => {
        document.body.classList.remove('darkmode');
        localStorage.setItem('darkmode', null);
    }
    
    lightDarkModeSwitch.addEventListener('click', () => {
        darkmode = localStorage.getItem('darkmode');
        darkmode !== "active" ? enableDarkmode() : disableDarkmode();
    });
}