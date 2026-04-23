/**
 * Inicializē sākumlapas "Welcome" uznirstošo logu ar pamācību slaidrādi.
 * * Pārbauda localStorage, lai nerādītu logu atkārtoti, ja lietotājs to ir aizvēris, 
 * un pielāgo attēlus un to izmērus atkarībā no ekrāna platuma (responsive).
 */

export function initHomeWelcomePopup() {
    const isPopupDisabled = localStorage.getItem('welcomePopupDisabled') === 'true';
    if (isPopupDisabled) return;

    const popupOverlay = document.querySelector('.popup-overlay');
    const welcomePopup = document.getElementById('home-welcome-popup');
    const dontShowCheckbox = document.getElementById('dont-show-again');
    const closeBtn = document.getElementById('close-welcome-btn');
    //slider elements
    const displayImg = document.getElementById('tutorial-img');
    const nextBtn = document.getElementById('next-tutorial-btn');
    const prevBtn = document.getElementById('prev-tutorial-btn');


    const width = window.innerWidth;
    let attr = '';

    if (width < 700) {
        attr = 'data-narrow-images';
    } else if (width >= 700 && width < 900) {
        attr = 'data-medium-images';
    } else {
        attr = 'data-wide-images';
    }

    const tutorialImages = JSON.parse(welcomePopup.getAttribute(attr));
    let currentIndex = 0;

    function updateSlider() {
        displayImg.src = tutorialImages[currentIndex];
   
        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex === tutorialImages.length - 1;
 
        prevBtn.style.opacity = prevBtn.disabled ? "0.5" : "1";
        nextBtn.style.opacity = nextBtn.disabled ? "0.5" : "1";
    }

    if (welcomePopup && popupOverlay) {
        welcomePopup.style.display = 'block';
        popupOverlay.classList.add('show');
        updateSlider();
    }

    nextBtn.addEventListener('click', () => {
        if (currentIndex < tutorialImages.length - 1) {
            currentIndex++;
            updateSlider();
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
        }
    });

    function handleClose() {
        localStorage.setItem('welcomePopupDisabled', 'true');
        welcomePopup.style.display = 'none';
        popupOverlay.classList.remove('show');
    }

    closeBtn.addEventListener('click', handleClose);
    // popupOverlay.addEventListener('click', (event) => {
    //     if (event.target === popupOverlay) handleClose();
    // });
}