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
import { initExtraExperienceLoad } from './modules/home/extraExperienceLoad.js';
import { initSettings } from './modules/settings.js';
import { initExperienceCreateMediaUpload } from './modules/experienceCreateMediaUpload.js';
import { initExperienceShowAjaxRequests } from './modules/experienceShowAjaxRequests.js';
import { initLists } from './modules/lists.js';
import { initAddOutsideLinkInput } from './modules/experienceCreate/addOutsideLinkInput.js';
import { initSearch } from './modules/search.js';
import { initExtraUserExperienceLoad } from './modules/user/extraUserExperienceLoad.js';
import { initExtraYourExperiencesLoad } from './modules/your-experiences/extraYourExperiencesLoad.js';
import { initExtraUserFollowingLoad } from './modules/user/extraUserFollowingLoad.js';
import { initPageRefreshErrorToast } from './modules/pageRefreshErrorToast.js';

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

    if (document.querySelector('.home-container')) {
        initExtraExperienceLoad();
    }

    if (document.querySelector('#settings-profile-picture-input')) {
        initSettings();
        initPageRefreshErrorToast();
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

    if (document.querySelector('#search-results')) {
        initSearch();
    }

    if (document.querySelector('.profile-experience-container')) {
        initExtraUserExperienceLoad();
    }

    if (document.querySelector('.your-experiences-content')) {
        initExtraYourExperiencesLoad();
    }

    if (document.querySelector('.followers')) {
        initExtraUserFollowingLoad();
    }
});