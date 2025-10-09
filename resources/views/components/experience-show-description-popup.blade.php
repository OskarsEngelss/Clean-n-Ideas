@push('popup')
    <div id="experience-show-description-popup">
        <div class="experience-show-popup-close-container">
            <button class="experience-show-popups-off-button">
                <svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="var(--text-color)">
                    <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
                </svg>
            </button>
        </div>
        <div>
            <h4>Description:</h4>
            <p>{{ $experience->description }}</p>
        </div>
    </div>
@endpush