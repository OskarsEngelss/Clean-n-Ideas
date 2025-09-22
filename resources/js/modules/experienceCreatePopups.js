//NEEDS IMPROVEMENT!!!!!!!!!!!

export function initExperienceCreatePopups() {
    const categoryInput = document.getElementById('category-input');
    const visibilityInput = document.getElementById('experience-visibility-input');

    const categoryPopup = document.getElementById('category-popup');
    const visibilityPopup = document.getElementById('visibility-popup');

    const categoryOptionList = categoryPopup.querySelectorAll('.category-popup-options');
    const visibilityOptionList = visibilityPopup.querySelectorAll('.visibility-popup-options');

    const popupOverlay = document.querySelector('.popup-overlay');

    function toggleCategoryPopup() {
        visibilityPopup.style.display = "none";
        categoryPopup.style.display = "block";
        popupOverlay.classList.toggle('show');
    }
    function toggleVisibilityPopup() {
        categoryPopup.style.display = "none";
        visibilityPopup.style.display = "block";
        popupOverlay.classList.toggle('show');
    }

    categoryInput.addEventListener('click', toggleCategoryPopup);
    visibilityInput.addEventListener('click', toggleVisibilityPopup);


    categoryOptionList.forEach((option) => {
        option.addEventListener('click', () => {
            selectOptionForCategory(option);
        });
    });
    visibilityOptionList.forEach((option) => {
        option.addEventListener('click', () => {
            selectOptionForVisibility(option);
        });
    });

    function selectOptionForCategory(option) {
        categoryInput.value = option.textContent;
        toggleCategoryPopup();
    }
    function selectOptionForVisibility(option) {
        visibilityInput.value = option.textContent;
        toggleVisibilityPopup();
    }

    popupOverlay.addEventListener('click', (event) => {
        if (event.target === popupOverlay) {
            toggleCategoryPopup();
        }
    });
}