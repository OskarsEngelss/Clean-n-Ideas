export function initAddOutsideLinkInput() {
    const addOutsideLinkInput = document.getElementById('add-outside-link-input');
    const addOutsideLinkButton = document.getElementById('add-outside-link-button');
    const urlList = document.getElementById('added-url-list');
    const experienceCreateForm = document.getElementById('create-tutorial-form');

    let urls = [];

    function isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch (_) {
            return false;
        }
    }

    addOutsideLinkButton.addEventListener('click', function () {
        const url = addOutsideLinkInput.value.trim();
        const id = 'url-' + Date.now(); 

        if (!url || !isValidUrl(url)) {
            alert('Invalid URL');
            return;
        }

        if (urls.includes(url)) {
            alert('URL already added');
            return;
        }

        urls.push(url);

        // Display visually
        const div = document.createElement('div');
        div.classList = "added-url-container";
        div.dataset.url = url;
        div.dataset.id = id;
        div.innerHTML = `<a href="${url}" target="_blank">${url}</a>`;

        const removeButton = document.createElement('button');
        removeButton.classList = "remove-added-url-button";
        removeButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="var(--text-color)">
                <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
            </svg>
        `;
        div.appendChild(removeButton);
        urlList.appendChild(div);

        // Add hidden input to form
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'urls[]';
        hidden.value = url;
        hidden.dataset.id = id;
        experienceCreateForm.appendChild(hidden);

        // Clear input
        addOutsideLinkInput.value = '';
    });

    document.getElementById('add-outside-links-popup').addEventListener('click', (event) => {
        const button = event.target.closest('.remove-added-url-button');
        if (!button) return;

        const addedUrlContainer = button.parentElement;
        if (!addedUrlContainer) return;

        // remove hidden input in the form
        const hiddenInput = experienceCreateForm.querySelector(`input[type="hidden"][data-id="${addedUrlContainer.dataset.id}"]`);
        if (hiddenInput) hiddenInput.remove();

        const urlToRemove = addedUrlContainer.dataset.url;
        if (!urlToRemove) return;

        urls = urls.filter(url => url !== urlToRemove);

        addedUrlContainer.remove();
    }) 
}