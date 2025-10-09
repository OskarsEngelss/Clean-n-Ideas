export function initExperienceShowAjaxRequests() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


    //AJAX for favourite button //AJAX for favourite button //AJAX for favourite button //AJAX for favourite button //AJAX for favourite button
    //AJAX for favourite button //AJAX for favourite button //AJAX for favourite button //AJAX for favourite button //AJAX for favourite button

    const favouriteForm = document.getElementById('experience-show-favourite-form');
    const favouriteButton = document.getElementById('experience-show-favourite-button');

    const filledSVG = document.getElementById('svg-filled')?.innerHTML;
    const emptySVG = document.getElementById('svg-empty')?.innerHTML;

    favouriteForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(favouriteForm);
        const data = Object.fromEntries(formData.entries());

        const response = await fetch(favouriteForm.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        favouriteButton.innerHTML = result.favourited ? filledSVG : emptySVG;
    });



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

    
}