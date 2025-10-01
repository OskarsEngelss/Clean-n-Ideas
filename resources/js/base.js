export function initBase() {
    let darkmode = localStorage.getItem('darkmode');
    const enableDarkmode = () => {
        document.body.classList.add('darkmode');
        localStorage.setItem('darkmode', 'active');
    }
    if (darkmode === "active") enableDarkmode();

    const sidebar = document.getElementById('sidebar');
    const sidebarToggleButton = document.getElementById('sidebar-toggle-button');
    const menuIcon = sidebarToggleButton.querySelector('.menu-icon');
    const closeIcon = sidebarToggleButton.querySelector('.close-icon');

    sidebarToggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('show-left');
        menuIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    });

    const nav = document.querySelector('nav');
    const searchForm = document.getElementById('search-form');
    const smallSearchButton = document.getElementById('small-search-button');
    const smallSearchButtonContainer = document.querySelector('.small-search-button-container');
    const profileContainer = document.querySelector('.nav-profile');
    const searchToggleOffButton = document.getElementById('search-toggle-off-button');


    const toggleSearch = () => {
        nav.classList.toggle('full-search');
        searchForm.classList.toggle('show');
        smallSearchButtonContainer.classList.toggle('hidden');
        profileContainer.classList.toggle('hidden');
        sidebarToggleButton.classList.toggle('hidden');
        searchToggleOffButton.classList.toggle('show');
    };
    smallSearchButton.addEventListener('click', toggleSearch);
    searchToggleOffButton.addEventListener('click', toggleSearch);
}
