import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { initBase } from './base.js';
import { initProfilePopup } from './modules/profilePopup';
import { initExperienceCreatePopups } from './modules/experienceCreatePopups.js';
import { initNavbarExtensionToggleButton } from './modules/navbarExtensionToggleButton.js';
import { initExperienceShowPopups } from './modules/experienceShowPopups.js';
import { initExperienceShowPopupsEmojiPicker } from './modules/experienceShowPopupsEmojiPicker.js';
import { initExperienceShowCommentsForm } from './modules/experienceShowCommentsForm.js';
import { initLightDarkMode } from './modules/light-dark-mode.js';
import { initCarousel } from './modules/home/carousel.js';
import { initSettings } from './modules/settings.js';
import { initExperienceCreateMediaUpload } from './modules/experienceCreateMediaUpload.js';
import { initExperienceShowAjaxRequests } from './modules/experienceShowAjaxRequests.js';
import { initLists } from './modules/lists.js';
import { initAddOutsideLinkInput } from './modules/experienceCreate/addOutsideLinkInput.js';

document.addEventListener('DOMContentLoaded', () => {
    initBase();

    if (document.querySelector('#navbar-extension')) {
        initNavbarExtensionToggleButton();
    }

    if (document.querySelector('#profile-more-info-button')) {
        initProfilePopup();
    }

    if (document.querySelector('#category-popup')) {
        initExperienceCreatePopups();
        initAddOutsideLinkInput();
    }

    if (document.querySelector('#experience-show-description-popup')) {
        initExperienceShowPopups();
    }

    if (document.querySelector('#experience-show-comments-popup-emoji-picker')) {
        initExperienceShowPopupsEmojiPicker();
    }

    if (document.querySelector('#experience-show-comments-popup-form')) {
        initExperienceShowCommentsForm();
    }

    if (document.querySelector('#toggle-light-dark-mode')) {
        initLightDarkMode();
    }

    if (document.querySelector('#home-carousel')) {
        initCarousel();
    }

    if (document.querySelector('#settings-profile-picture-input')) {
        initSettings();
    }

    if (document.querySelector('#publish-experience-add-image-button')) {
        initExperienceCreateMediaUpload();
    }

    if (document.querySelector('#experience-show-favourite-button')) {
        initExperienceShowAjaxRequests();
    }

    if (document.querySelector('#lists-make-new-list-button')) {
        initLists();
    }
});