export function initExperienceShowAjaxRequests() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


    //AJAX for favourite button //AJAX for favourite button //AJAX for favourite button //AJAX for favourite button //AJAX for favourite button
    //AJAX for favourite button //AJAX for favourite button //AJAX for favourite button //AJAX for favourite button //AJAX for favourite button

    const favouriteButton = document.getElementById('experience-show-favourite-button');

    favouriteButton.addEventListener('click', async () => {
        const tutorialListId = favouriteButton.dataset.listId;
        const tutorialId = favouriteButton.dataset.tutorialId;

        const data = {
            tutorial_list_id: tutorialListId,
            tutorial_id: tutorialId,
        };

        const response = await fetch('/lists/storeTutorial', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const svg = favouriteButton.querySelector('svg');

        if (favouriteButton.classList.contains('favourited-check')) {
            favouriteButton.classList.remove('favourited-check');
            svg.innerHTML = `<path class="default" d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/>`;
        } else {
            favouriteButton.classList.add('favourited-check');
            svg.innerHTML = `<path class="default" d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Z"/>`;
        }

        // if (button.classList.contains('saved')) {
        //     button.classList.remove('saved');
        //     button.textContent = "Add";
        // } else {
        //     button.classList.add('saved');
        //     button.textContent = "Remove";
        // }
    })



    //AJAX for comments/reply posting //AJAX for comments/reply posting //AJAX for comments/reply posting //AJAX for comments/reply posting
    //AJAX for comments/reply posting //AJAX for comments/reply posting //AJAX for comments/reply posting //AJAX for comments/reply posting

    const experienceShowCommentsPopupForm = document.getElementById('experience-show-comments-popup-form');

    experienceShowCommentsPopupForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(experienceShowCommentsPopupForm);

        try {
            const response = await fetch(experienceShowCommentsPopupForm.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            if (!response.ok) {
                const data = await response.json();
                console.error(data);
                return;
            }

            const data = await response.json();
            console.log(data);
            insertCommentHtml(data.html, data.parent_id);
            experienceShowCommentsPopupForm.reset(); // clear form

            //Code to clear the input after a reply submission //Code to clear the input after a reply submission //Code to clear the input after a reply
            const input = document.getElementById('experience-show-comments-popup-input');
            const buttonContainer = document.getElementById('experience-show-comments-popup-button-container');
            const submitButton = document.getElementById('experience-show-comments-popup-submit-button');
            const emojiPicker = document.getElementById('experience-show-comments-popup-emoji-picker');
            const parentInput = document.getElementById('parent_id');
            buttonContainer.classList.remove('show');
            emojiPicker.style.display = 'none';
            input.value = '';
            input.style.height = '';
            input.placeholder = "Comment...";
            submitButton.textContent = "Comment";
            parentInput.value = "";
        } catch (error) {
            console.error('AJAX comment submission failed', error);
        }

    });
    
    function insertCommentHtml(html, parentId) {
        if (parentId) {
            // Find the base comment container
            const parentComment = document.querySelector(`.experience-show-comment-container[data-comment="${parentId}"]`);
            if (!parentComment) {
                console.warn('Parent comment not found:', parentId);
                return;
            }

            // Check for existing reply container
            const toggleContainer = parentComment.nextElementSibling;

            if (toggleContainer && toggleContainer.classList.contains('experience-show-comment-reply-and-toggle-container')) {
                const toggleButton = toggleContainer.querySelector('.experience-show-comment-toggle-reply-container-button');
                let count = parseInt(toggleButton.dataset.replyCount) || 0;
                count++;
                toggleButton.dataset.replyCount = count;

                const repliesContainer = toggleButton.nextElementSibling;
                if (!repliesContainer.classList.contains('show')) {
                    toggleButton.textContent = `${count} ${count === 1 ? 'Reply' : 'Replies'}`;
                }

                const innerDiv = repliesContainer.querySelector('div');
                innerDiv.insertAdjacentHTML('beforeend', html);
            } else {
                const newToggleContainer = document.createElement('div');
                newToggleContainer.classList.add('experience-show-comment-reply-and-toggle-container');

                const toggleButton = document.createElement('button');
                toggleButton.classList.add('experience-show-comment-toggle-reply-container-button');
                toggleButton.dataset.replyCount = 1;
                toggleButton.textContent = '1 Reply';

                const replyContainer = document.createElement('div');
                replyContainer.classList.add('experience-show-comment-reply-container');

                const innerDiv = document.createElement('div');
                innerDiv.innerHTML = html;
                replyContainer.appendChild(innerDiv);

                newToggleContainer.appendChild(toggleButton);
                newToggleContainer.appendChild(replyContainer);

                parentComment.insertAdjacentElement('afterend', newToggleContainer);
            }
        } else {
            // Top-level comment: insert after last base comment in popup
            const popup = document.getElementById('experience-show-comments-popup');
            if (!popup) return;

            const noCommentsMsg = document.getElementById('no-comments-message');
            const firstRealComment = document.querySelector('.experience-show-comment-container');
            if (noCommentsMsg && !firstRealComment) {
                noCommentsMsg.remove();
            }

            const commentContainer = popup.querySelector('.experience-show-popup-comments-container');
            commentContainer.insertAdjacentHTML('afterbegin', html);
        }
    }



    //Comment reaction form AJAX //Comment reaction form AJAX //Comment reaction form AJAX //Comment reaction form AJAX //Comment reaction form AJAX
    //Comment reaction form AJAX //Comment reaction form AJAX //Comment reaction form AJAX //Comment reaction form AJAX //Comment reaction form AJAX

    document.querySelectorAll('.experience-show-comment-reaction-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const commentId = form.dataset.commentId;
            const type = form.dataset.type;
            const button = form.querySelector('button'); // clicked button
            const otherType = type === 'like' ? 'dislike' : 'like';
            const otherButton = document.querySelector(`.experience-show-comment-reaction-form[data-comment-id="${commentId}"][data-type="${otherType}"] button`);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        comment_id: commentId,
                        type: type,
                    }),
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const result = await response.json();

                document.getElementById(`like-count-${commentId}`).textContent = result.likes;
                document.getElementById(`dislike-count-${commentId}`).textContent = result.dislikes;

                if (result.status === 'added' || result.status === 'updated') {
                    button.classList.add('active');
                    if (otherButton) otherButton.classList.remove('active');
                } else if (result.status === 'removed') {
                    button.classList.remove('active');
                }

                console.log(`Reaction ${result.status}: ${type}`);
            } catch (error) {
                console.error('Error submitting reaction:', error);
            }
        });
    });



    //Tutorial reaction form AJAX //Tutorial reaction form AJAX //Tutorial reaction form AJAX //Tutorial reaction form AJAX //Tutorial reaction form AJAX
    //Tutorial reaction form AJAX //Tutorial reaction form AJAX //Tutorial reaction form AJAX //Tutorial reaction form AJAX //Tutorial reaction form AJAX

    document.querySelectorAll('.experience-show-tutorial-reaction-form').forEach(form => {
        form.addEventListener('submit', async e => {
            e.preventDefault();

            const tutorialId = form.dataset.tutorialId;
            const type = form.dataset.type;
            const button = form.querySelector('button');
            const otherType = type === 'like' ? 'dislike' : 'like';
            const otherButton = document.querySelector(`.experience-show-tutorial-reaction-form[data-tutorial-id="${tutorialId}"][data-type="${otherType}"] button`);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        tutorial_id: tutorialId,
                        type: type
                    }),
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const result = await response.json();

                const likeEl = document.getElementById(`tutorial-like-count-${tutorialId}`);
                const dislikeEl = document.getElementById(`tutorial-dislike-count-${tutorialId}`);
                if (likeEl) likeEl.textContent = result.likes;
                if (dislikeEl) dislikeEl.textContent = result.dislikes;

                if (result.status === 'added' || result.status === 'updated') {
                    button.classList.add('active');
                    if (otherButton) otherButton.classList.remove('active');
                } else if (result.status === 'removed') {
                    button.classList.remove('active');
                }

            } catch (error) {
                console.error('Error submitting reaction:', error);
            }
        });
    });



    //Save to tutorial list AJAX //Save to tutorial list AJAX //Save to tutorial list AJAX //Save to tutorial list AJAX //Save to tutorial list AJAX
    //Save to tutorial list AJAX //Save to tutorial list AJAX //Save to tutorial list AJAX //Save to tutorial list AJAX //Save to tutorial list AJAX
    const experienceShowListsPopup = document.getElementById('experience-show-lists-popup');

    experienceShowListsPopup.addEventListener('click', async (e) => {
        const button = e.target.closest('.experience-show-list-add-button');
        if (!button) return;

        const tutorialListId = button.dataset.listId;
        const tutorialId = button.dataset.tutorialId;

        const data = {
            tutorial_list_id: tutorialListId,
            tutorial_id: tutorialId,
        };

        const response = await fetch('/lists/storeTutorial', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        if (button.classList.contains('saved')) {
            button.classList.remove('saved');
            button.textContent = "Add";
        } else {
            button.classList.add('saved');
             button.textContent = "Remove";
        }
    });
    
    
    //Make new list and save to it AJAX //Make new list and save to it AJAX //Make new list and save to it AJAX //Make new list and save to it AJAX
    //Make new list and save to it AJAX //Make new list and save to it AJAX //Make new list and save to it AJAX //Make new list and save to it AJAX
    const newListForm = document.getElementById('new-list-form');
    const listContainer = document.querySelector('.experience-show-list-option-container');

    newListForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(newListForm);

        const experienceId = newListForm.dataset.tutorialId;
        formData.append('experience_id', experienceId ?? '');

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
            console.log(data);
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
    const experienceShowListOptionContainer = document.querySelector(".experience-show-list-option-container");
    const trigger = document.querySelector(".experience-show-list-load-more-trigger");
    let page = 1;
    let isLoading = false;
    let noMoreLists = false;
    const experienceSlug = experienceShowListOptionContainer.dataset.experienceSlug;
    const experienceId = experienceShowListOptionContainer.dataset.experienceId;

    const loadMoreLists = async () => {
        if (isLoading || noMoreLists) return;
        isLoading = true;
        page++;

        try {
            const response = await fetch(`/experiences/${experienceSlug}/lists/load-more?page=${page}`, {
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!response.ok) throw new Error("Network error");

            const data = await response.text();
            if (data.trim() === "") {
                noMoreLists = true;
                observer.disconnect();
                console.log("No more lists to load.");
                return;
            }

            experienceShowListOptionContainer.insertAdjacentHTML("beforeend", data);
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