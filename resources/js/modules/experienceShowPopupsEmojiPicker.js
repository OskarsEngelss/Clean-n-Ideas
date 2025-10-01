export function initExperienceShowPopupsEmojiPicker() {
    const input = document.getElementById('experience-show-comments-popup-input');
    const picker = document.getElementById('experience-show-comments-popup-emoji-picker');
    const toggleBtn = document.getElementById('experience-show-comments-popup-emoji-button');

    // Toggle visibility
    toggleBtn.addEventListener('click', () => {
        picker.style.display = picker.style.display === 'none' ? 'block' : 'none';

        function clickedOutsideEmojiPicker(event) {
            if (!picker.contains(event.target) && event.target !== toggleBtn) {
                picker.style.display = picker.style.display = 'none';
                document.removeEventListener('click', clickedOutsideEmojiPicker);
            }
        }

        document.addEventListener('click', clickedOutsideEmojiPicker);
    });

    // Insert emoji on click
    picker.addEventListener('emoji-click', event => {
        const emoji = event.detail.unicode;
        const cursorPos = input.selectionStart;
        const textBefore = input.value.substring(0, cursorPos);
        const textAfter = input.value.substring(cursorPos);
        input.value = textBefore + emoji + textAfter;
        input.focus();
        input.selectionEnd = cursorPos + emoji.length;
    });
}