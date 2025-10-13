export function initLists() {
    const popupOverlay = document.querySelector('.popup-overlay');

    let activePopup = null;

    function closeActivePopup() {
        if (activePopup) {
            activePopup.popup.style.display = 'none';
            popupOverlay.classList.remove('show');
            activePopup = null;
        }
    }

    function openPopup(popupElement) {
        closeActivePopup();
        popupElement.id == "experience-show-comments-popup" ? popupElement.style.display = 'grid' : popupElement.style.display = 'block';
        popupOverlay.classList.add('show');
        activePopup = { popup: popupElement };
    }

    const popupButtons = document.querySelectorAll('[data-popup-target]');

    popupButtons.forEach(button => {
        const popupId = button.getAttribute('data-popup-target');
        const popupElement = document.getElementById(popupId);

        button.addEventListener('click', () => openPopup(popupElement));
    });

    popupOverlay.addEventListener('click', (event) => {
        if (event.target === popupOverlay || event.target.closest('.popup-close-button')) {
            closeActivePopup();
        }
    });


    //List storeList AJAX //List storeList AJAX //List storeList AJAX //List storeList AJAX //List storeList AJAX //List storeList AJAX //List storeList AJAX
    //List storeList AJAX //List storeList AJAX //List storeList AJAX //List storeList AJAX //List storeList AJAX //List storeList AJAX //List storeList AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const newListForm = document.getElementById('new-list-form');
    const listContainer = document.querySelector('.lists');

    newListForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(newListForm);

        try {
            const response = await fetch(newListForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (!response.ok) throw new Error("Network error");

            const data = await response.text();
            if (data.trim() === "") {
                console.log("Nothing was made?");
                return;
            }

            listContainer.insertAdjacentHTML("afterbegin", data);
        } catch (error) {
            console.error("Error loading posts:", error);
        }
    });


    //Load more lists AJAX //Load more lists AJAX //Load more lists AJAX //Load more lists AJAX //Load more lists AJAX //Load more lists AJAX
    //Load more lists AJAX //Load more lists AJAX //Load more lists AJAX //Load more lists AJAX //Load more lists AJAX //Load more lists AJAX
    const listIndexListContainer = document.querySelector(".lists");
    const trigger = document.querySelector("footer");
    let page = 1;
    let isLoading = false;
    let noMoreLists = false;
    const authUserId = listIndexListContainer.dataset.authUserId;

    const loadMoreLists = async () => {
        if (isLoading || noMoreLists) return;
        isLoading = true;
        page++;

        try {
            const response = await fetch(`/lists/${authUserId}/load-more?page=${page}`, {
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) throw new Error("Network error");

            const data = await response.text();
            if (data.trim() === "") {
                noMoreLists = true;
                observer.disconnect();
                console.log("No more lists to load.");
                return;
            }

            listIndexListContainer.insertAdjacentHTML("beforeend", data);
        } catch (error) {
            console.error("Error loading posts:", error);
        } finally {
            isLoading = false;
        }
    };

    const observer = new IntersectionObserver(
        entries => {
            if (entries[0].isIntersecting) {
                loadMoreLists();
            }
        },
        { threshold: 0.5 }
    );

    observer.observe(trigger);
}