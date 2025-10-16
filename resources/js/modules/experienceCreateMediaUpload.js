export function initExperienceCreateMediaUpload() {
    //CSRF TOKEN!!!!
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const experienceCreateForm = document.getElementById('create-tutorial-form');
    const addImageButton = document.getElementById('publish-experience-add-image-button');
    const imagePreviewContainer = document.getElementById('publish-experience-image-preview-container');

    const addVideoButton = document.getElementById('publish-experience-add-video-button');
    const videoPreviewContainer = document.getElementById('publish-experience-video-preview-container');
    const backgroundVideoUploads = new Map();
    const activeUploads = new Map();

    window.addEventListener('beforeunload', async () => {
        if (uploadedTempPaths.length === 0) return;

        navigator.sendBeacon('/cleanup-temp', JSON.stringify({
            paths: uploadedTempPaths
        }));
    });


    //Dragging media to tutorial input
    function handleImageDragging(event) {
        event.preventDefault();

        const isTouch = event.type.startsWith('touch');
        const pointerEvent = isTouch ? event.touches[0] : event;

        const media = event.currentTarget;

        const mediaId = media.dataset.mediaId || `media-${Date.now()}-${Math.floor(Math.random() * 1000)}`;

        media.setAttribute('draggable', 'false');
        const restoreDragStart = media.ondragstart;
        media.ondragstart = (e) => e.preventDefault();

        const prevTouchAction = document.body.style.touchAction;
        const prevUserSelect = document.body.style.userSelect;
        document.body.style.touchAction = 'none';
        document.body.style.userSelect = 'none';

        function preventContext(e) { e.preventDefault(); }
        document.addEventListener('contextmenu', preventContext);

        const clone = media.cloneNode(true);
        clone.className = "media-clone dragging";
        clone.style.pointerEvents = 'none';
        clone.style.position = 'absolute';
        clone.style.zIndex = '1000';
        document.body.appendChild(clone);

        moveAt(pointerEvent.pageX, pointerEvent.pageY);

        function moveAt(pageX, pageY) {
            clone.style.left = (pageX - clone.offsetWidth / 2) + 'px';
            clone.style.top = (pageY - clone.offsetHeight / 2) + 'px';
        }

        function insertMediaAtPosition(x, y) {
            const targetElement = document.elementFromPoint(x, y);
            const range = document.caretRangeFromPoint
                ? document.caretRangeFromPoint(x, y)
                : document.caretPositionFromPoint?.(x, y);

            if (range && editor.contains(targetElement)) {
                const wrapper = document.createElement('span');
                wrapper.className = "tutorial-textarea-media-wrapper";

                const insertedMediaRemoveButtonContainer = document.createElement('div');
                insertedMediaRemoveButtonContainer.className = "inserted-media-remove-button-container";

                const insertedMedia = media.cloneNode(true);
                insertedMedia.src = media.dataset.tempPath || media.src;
                insertedMedia.className = "inserted-media";
                if (insertedMedia.tagName.toLowerCase() === 'video') {
                    insertedMedia.controls = true;
                }

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'publish-experience-media-preview-remove-button';
                removeButton.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="var(--text-color)">
                            <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
                        </svg>
                    `;
                removeButton.addEventListener('click', () => wrapper.remove());

                insertedMediaRemoveButtonContainer.appendChild(insertedMedia);
                insertedMediaRemoveButtonContainer.appendChild(removeButton);
                wrapper.appendChild(insertedMediaRemoveButtonContainer);

                let container = range.startContainer;
                while (container && container !== editor) {
                    if (container.classList && container.classList.contains('tutorial-textarea-media-wrapper')) {
                        range.setStartAfter(container);
                        range.setEndAfter(container);
                        break;
                    }
                    container = container.parentNode;
                }

                range.insertNode(wrapper);

                range.setStartAfter(wrapper);
                range.setEndAfter(wrapper);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
            }
        }

        function cleanup() {
            document.body.style.touchAction = prevTouchAction;
            document.body.style.userSelect = prevUserSelect;
            document.removeEventListener('contextmenu', preventContext);

            media.ondragstart = restoreDragStart;

            if (clone && clone.parentNode) clone.parentNode.removeChild(clone);
        }

        if (isTouch) {
            function onTouchMove(e) {
                const touch = e.touches[0];
                moveAt(touch.pageX, touch.pageY);

                const margin = 50;
                const scrollSpeed = 10;

                if (touch.clientY < margin) {
                    window.scrollBy(0, -scrollSpeed);
                } else if (touch.clientY > window.innerHeight - margin) {
                    window.scrollBy(0, scrollSpeed);
                }
            }

            function onTouchEnd(e) {
                const touch = e.changedTouches[0];
                insertMediaAtPosition(touch.clientX, touch.clientY);

                document.removeEventListener('touchmove', onTouchMove, { passive: false });
                document.removeEventListener('touchend', onTouchEnd);
                cleanup();
            }

            document.addEventListener('touchmove', onTouchMove, { passive: false });
            document.addEventListener('touchend', onTouchEnd);
        } else {
            function onMouseMove(e) {
                e.preventDefault();
                moveAt(e.pageX, e.pageY);
            }

            function onMouseUp(e) {
                document.removeEventListener('mousemove', onMouseMove);
                document.removeEventListener('mouseup', onMouseUp);

                insertMediaAtPosition(e.clientX, e.clientY);
                cleanup();
            }

            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
        }
    }



    const handleMediaUpload = (event) => {
        const isImage = event.target.id === 'publish-experience-add-image-button';
        const isVideo = event.target.id === 'publish-experience-add-video-button';

        const fileInput = document.createElement('input');
        fileInput.type = "file";
        fileInput.accept = isImage ? "image/*" : "video/*";

        fileInput.addEventListener('change', async () => {
            const file = fileInput.files[0];
            if (!file) return;

            const MAX_FILE_SIZE_MB = 200;
            const MAX_FILE_SIZE_BYTES = MAX_FILE_SIZE_MB * 1024 * 1024;

            if (file.size > MAX_FILE_SIZE_BYTES) {
                const errorToast = document.createElement('div');
                errorToast.className = 'floating-error-toast';
                errorToast.innerText = `File too large. Maximum allowed size is ${MAX_FILE_SIZE_MB} MB.`;

                document.body.appendChild(errorToast);

                requestAnimationFrame(() => {
                    errorToast.classList.add('show');
                });

                setTimeout(() => {
                    errorToast.classList.remove('show');
                    setTimeout(() => errorToast.remove(), 500);
                }, 3000);

                return;
            }

            const preview = document.createElement('div');
            preview.className = "publish-experience-media-preview";

            const media = document.createElement(isImage ? 'img' : 'video');
            const mediaId = `media-${Date.now()}-${Math.floor(Math.random() * 1000)}`;
            media.setAttribute('draggable', 'false');
            media.oncontextmenu = (e) => e.preventDefault();
            media.setAttribute('data-media-id', mediaId);

            const removeButton = document.createElement('button');
            removeButton.type = "button";
            removeButton.className = "publish-experience-media-preview-remove-button";
            removeButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="var(--text-color)">
                    <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
                </svg>
            `;

            removeButton.addEventListener('click', () => {
                preview.remove();
                fileInput.remove();

                const isImageFile = media.tagName.toLowerCase() === 'img';
                const isVideoFile = media.tagName.toLowerCase() === 'video';

                const tempPath = media.dataset.tempPath || media.src;
                if (tempPath && tempPath.startsWith('/storage/temp/')) {
                    fetch('/delete-temp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ path: tempPath })
                    });
                }

                if (isVideoFile && backgroundVideoUploads.has(mediaId)) {
                    const uploadEntry = backgroundVideoUploads.get(mediaId);
                    uploadEntry.controller.abort();
                    backgroundVideoUploads.delete(mediaId);
                }

                const insertedInstances = editor.querySelectorAll(`[data-media-id="${mediaId}"]`);
                insertedInstances.forEach(instance => instance.closest('.tutorial-textarea-media-wrapper').remove());
            });

            preview.appendChild(media);
            preview.appendChild(removeButton);

            const previewContainer = isImage ? imagePreviewContainer : videoPreviewContainer;
            previewContainer.appendChild(preview);


            const overlay = document.createElement('div');
            overlay.className = 'media-upload-overlay';
            overlay.innerHTML = `<div class="spinner"></div>`;
            preview.appendChild(overlay);

            function bindMediaDragEvents(media) {
                media.addEventListener('mousedown', handleImageDragging);
                media.addEventListener('touchstart', handleImageDragging);
            }

            if (isImage) {
                const formData = new FormData();
                formData.append('file', file);
                const response = await fetch('/upload-temp', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await response.json();
                uploadedTempPaths.push(data.tempPath);
                media.src = data.tempPath;
                media.dataset.tempPath = data.tempPath;
                bindMediaDragEvents(media);

                preview.removeChild(overlay);
            } else {
                media.src = URL.createObjectURL(file);
                media.controls = false;

                const controller = new AbortController();
                const uploadPromise = uploadVideoInBackground(file, controller)
                    .then(tempPath => {
                        media.dataset.tempPath = tempPath;
                        preview.removeChild(overlay);
                        
                        bindMediaDragEvents(media);
                    }).catch(error => {
                        if (error.name === 'AbortError') {
                            console.log(`Upload for ${mediaId} aborted.`);
                        } else {
                            console.error('Upload failed:', error);
                        }
                        preview.removeChild(overlay);
                    }).finally(() => {
                        activeUploads.delete(mediaId);
                    });

                activeUploads.set(mediaId, uploadPromise);
                backgroundVideoUploads.set(mediaId, { promise: uploadPromise, controller });
            }

            fileInput.style.display = "none";
            experienceCreateForm.appendChild(fileInput);
        });

        
        fileInput.click();
    };

    addImageButton.addEventListener('click', handleMediaUpload);
    addVideoButton.addEventListener('click', handleMediaUpload);

    let uploadedTempPaths = [];

    async function uploadVideoInBackground(file, controller) {
        const formData = new FormData();
        formData.append('file', file);

        const response = await fetch('/upload-temp', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            signal: controller.signal,
            body: formData
        });

        const data = await response.json();
        uploadedTempPaths.push(data.tempPath);
        return data.tempPath;
    }


    //TEXT EDITOR AND STUFF   //TEXT EDITOR AND STUFF   //TEXT EDITOR AND STUFFv   //TEXT EDITOR AND STUFF   //TEXT EDITOR AND STUFF
    const editor = document.getElementById('tutorial-editor');
    editor.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace') {
            const selection = window.getSelection();
            if (!selection || !selection.rangeCount) return;

            const range = selection.getRangeAt(0);
            let node = range.startContainer;

            while (node && node !== editor) {
                if (node.nodeType === Node.ELEMENT_NODE && node.classList.contains('tutorial-textarea-media-wrapper')) {
                    e.preventDefault();
                    node.remove();
                    return;
                }
                node = node.parentNode;
            }
        }
    });

    //Placeholder text logic
    const placeholder = document.getElementById('editor-placeholder');

    const oldTutorialContent = document.getElementById('old-tutorial-content');
    if (oldTutorialContent && oldTutorialContent.value.trim() !== '') editor.innerHTML = oldTutorialContent.value;

    function togglePlaceholder() {
        const hasContent = Array.from(editor.childNodes).some(node => {
        if (node.nodeType === Node.TEXT_NODE && node.textContent.trim() !== '') return true;
        if (node.nodeType === Node.ELEMENT_NODE && node.tagName !== 'BR') return true;
        return false;
    });

    placeholder.style.display = hasContent ? 'none' : 'block';
    }

    togglePlaceholder();
    editor.addEventListener('input', togglePlaceholder);


    //Logic for submitting the form
    experienceCreateForm.addEventListener('submit', async (e) => {
        editorContent.value = editor.innerHTML;
        e.preventDefault();

        if (activeUploads.size > 0) {
            const errorToast = document.createElement('div');
            errorToast.className = 'floating-error-toast';
            errorToast.innerText = "Please wait: media files are still uploading.";

            document.body.appendChild(errorToast);

            requestAnimationFrame(() => {
                errorToast.classList.add('show');
            });

            setTimeout(() => {
                errorToast.classList.remove('show');
                setTimeout(() => errorToast.remove(), 500);
            }, 3000);

            return;
        }

        document.querySelectorAll('.form-error').forEach(el => el.remove());

        const formData = new FormData(experienceCreateForm);

        const xhr = new XMLHttpRequest();

        xhr.open('POST', experienceCreateForm.action, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        xhr.setRequestHeader('Accept', 'application/json');

        // Show progress bar
        const popupOverlay = document.querySelector('.popup-overlay');
        const uploadProgressPopup = document.getElementById('upload-progress-popup');

        const progressBar = document.getElementById('upload-progress');
        const progressTime = document.getElementById('upload-progress-time');
        popupOverlay.classList.add('show');
        uploadProgressPopup.style.display = 'block';
        progressBar.style.width = '0%';
        progressTime.innerText = '';

        const startTime = Date.now();

        xhr.upload.addEventListener('progress', (event) => {
            if (event.lengthComputable) {
                const percent = Math.round((event.loaded / event.total) * 100);
                progressBar.style.width = percent + '%';

                // Estimated time remaining
                const elapsed = (Date.now() - startTime) / 1000;
                const speed = event.loaded / elapsed;
                const remainingBytes = event.total - event.loaded;
                const eta = remainingBytes / speed;

                // Format as mm:ss
                const minutes = Math.floor(eta / 60);
                const seconds = Math.floor(eta % 60);
                progressTime.innerText = `Estimated time: ${minutes}m ${seconds}s`;
            }
        });

        xhr.onreadystatechange = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                popupOverlay.classList.remove('show');
                uploadProgressPopup.style.display = 'none';
                if (xhr.status === 200) {
                    const result = JSON.parse(xhr.responseText);
                    window.location.href = result.redirect;
                } else if (xhr.status === 422) {
                    const response = JSON.parse(xhr.responseText);
                    const errors = response.errors || {};

                    const errorMessages = Object.values(errors)
                        .map(msgArr => msgArr[0])
                        .join('\n');

                    const errorToast = document.createElement('div');
                    errorToast.className = 'floating-error-toast';
                    errorToast.innerText = errorMessages;

                    document.body.appendChild(errorToast);

                    requestAnimationFrame(() => {
                        errorToast.classList.add('show');
                    });

                    setTimeout(() => {
                        errorToast.classList.remove('show');
                        setTimeout(() => errorToast.remove(), 500);
                    }, 3000);
                } else {
                    console.error('Upload failed:', xhr.responseText);
                }
            }
        };

        xhr.send(formData);
    });
}
