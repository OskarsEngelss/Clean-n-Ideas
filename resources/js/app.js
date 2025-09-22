import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { initBase } from './base.js';
import { initProfilePopup } from './modules/profilePopup';
import { initExperienceCreatePopups } from './modules/experienceCreatePopups.js';

document.addEventListener('DOMContentLoaded', () => {
    initBase();

    if (document.querySelector('#profile-more-info-button')) {
        initProfilePopup();
    }

    if (document.querySelector('#category-popup')) {
        initExperienceCreatePopups();
    }
});