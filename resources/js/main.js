const links = [
    'profile-button',
    'website-title',
    'home-button',
    'favourites-button',
    'lists-button',
    'your-experiences-button',
    'following-button',
    'about-us-button',
    'experience-create-button',
];

links.forEach(id => {
    const el = document.getElementById(id);
    if (el) {
        el.addEventListener('click', (e) => {
            const url = el.dataset.url;
            if (!url) return;

            if (e.ctrlKey || e.metaKey || e.button === 1) {
                window.open(url, '_blank');
            } else {
                window.location.href = url;
            }
        });
    }
});

const button = document.getElementById('profile-picture');
const content = document.getElementById('profile-dropdown');

button.addEventListener('click', () => {
    if (content.style.display === 'none' || content.style.display === '') {
        content.style.display = 'block'; // show it
    } else {
        content.style.display = 'none';  // hide it
    }
});

//Logout button code!!
document.getElementById('logout-button')?.addEventListener('click', function() {
    document.getElementById('logout-form').submit();
});