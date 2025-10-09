@push('popup')
    <div id="add-outside-links-popup">
        <x-popup-close-component />
        <div>
            <div class="add-outside-link-input-button-container">
                <input id="add-outside-link-input" type="url" name="urls[]" class="default-input-style" placeholder="Enter URL" required>
                <button id="add-outside-link-button" type="button">Add</button>
            </div>
            <div id="added-url-list"></div>
        </div>
    </div>
@endpush