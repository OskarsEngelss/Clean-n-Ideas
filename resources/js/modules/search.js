// public/js/search.js
export function initSearch() {
    const searchResultsContainer = document.getElementById('search-results');
    const params = new URLSearchParams(window.location.search);
    const query = params.get('q') || '';

    if (!searchResultsContainer || !query) return;

    searchResultsContainer.addEventListener('click', async (e) => {
        const button = e.target.closest('.search-result-load-more-button');
        if (!button) return;

        const type = button.dataset.type;
        

        if (type === 'experiences') {
            let offset = parseInt(button.dataset.offset || 6);
            try {
                const res = await fetch(`/search?q=${encodeURIComponent(query)}&offset=${offset}&type=${type}`);
                const data = await res.json();

                const container = searchResultsContainer.querySelector('.search-result-experiences-container');

                data.experiences.items.forEach(exp => {
                    console.log(exp.user);
                    const div = document.createElement('div');
                    div.innerHTML = `
                        <a href="/experiences/${exp.slug}" class="tutorial-card-link">
                            <div class="tutorial-card">
                                <img class="thumbnail-preview" src="${exp.thumbnail}" alt="${exp.thumbnail} thumbnail">
                                <div class="tutorial-card-text-container">
                                    <h3 class="tutorial-card-title">${exp.title}</h3>
                                    <div class="tutorial-card-user-info-container">
                                        <img class="tutorial-card-profile-picture" alt="Profile icon" src="${exp.user.profile_picture ? '/storage/' + exp.user.profile_picture : '/images/defaults/default-profile-picture.jpg'}">
                                        <p class="tutorial-card-category">${exp.user.name}</p>
                                    </div>
                                    <p class="tutorial-card-favourites">Likes: ${exp.likes_count} | Dislikes: ${exp.dislikes_count}</p>
                                    <p class="tutorial-card-upload-time">${exp.created_at_human}</p>
                                </div>
                            </div>
                        </a>
                    `;
                    container.appendChild(div);
                });

                // Update offset
                button.dataset.offset = offset + data.experiences.items.length;

                // Hide button if no more
                if (!data.experiences.hasmore) button.style.display = 'none';

            } catch (err) {
                console.error('Error loading more experiences:', err);
            }

        } else if (type === 'users') {
            let offset = parseInt(button.dataset.offset || 4);
            try {
                const res = await fetch(`/search?q=${encodeURIComponent(query)}&offset=${offset}&type=${type}`);
                const data = await res.json();
                const container = searchResultsContainer.querySelector('.search-result-users-container');

                data.users.items.forEach(user => {
                    const a = document.createElement('a');
                    a.href = `/users/${user.id}`;
                    a.className = 'follower-card';
                    a.innerHTML = `
                        <h3>${user.name}</h3>
                        <p>${user.description || 'No about available.'}</p>
                        <div class="follower-card-icon-container">
                            <img alt="Profile icon"
                                src="${user.profile_picture ? '/storage/' + user.profile_picture : '/images/defaults/default-profile-picture.jpg'}">
                        </div>
                    `;
                    container.appendChild(a);
                });

                // Update offset
                button.dataset.offset = offset + data.users.items.length;

                // Hide button if no more
                if (!data.users.hasmore) button.style.display = 'none';

            } catch (err) {
                console.error('Error loading more users:', err);
            }
        }
            });
}
