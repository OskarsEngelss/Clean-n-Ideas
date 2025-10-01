export function initExperienceShowCommentsForm() {
    const input = document.getElementById('experience-show-comments-popup-input');
    const buttonContainer = document.getElementById('experience-show-comments-popup-button-container');
    const cancelButton = document.getElementById('experience-show-comments-popup-cancel-button');
    const submitButton = document.getElementById('experience-show-comments-popup-submit-button');
    const emojiPicker = document.getElementById('experience-show-comments-popup-emoji-picker');
    const replyButtons = document.querySelectorAll('.experience-show-comment-reply');
    const parentInput = document.getElementById('parent_id');

    input.addEventListener('click', () => {
        buttonContainer.classList.add('show');
    });

    replyButtons.forEach(button => {
        button.addEventListener('click', () => {
            const commentId = button.dataset.commentId;
            parentInput.value = commentId;
            buttonContainer.classList.add('show');
            input.placeholder = "Reply...";
            submitButton.textContent = "Reply";
        });
    });

    cancelButton.addEventListener('click', () => {
        buttonContainer.classList.remove('show');
        emojiPicker.style.display = 'none';
        input.value = '';
        input.style.height = '';
        input.placeholder = "Comment...";
        submitButton.textContent = "Comment";
        parentInput.value = "";
    });

    const textarea = document.getElementById('experience-show-comments-popup-input');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
}
