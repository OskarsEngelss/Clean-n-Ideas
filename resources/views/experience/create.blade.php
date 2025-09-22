<x-main-layout>
    <form action="{{ route('experience.store') }}" class="experience-form" id="create-tutorial-form" method="POST">
        @csrf
        <div class="publish-experience-button-container">
            <button>
                Publish
            </button>
        </div>
        <div class="title-input-container">
            <input value="{{ old('title') }}" class="title-input" type="text" name="title" placeholder="Title"/>
        </div>
        <div class="category-input-container">
            <input value="{{ old('category') }}" type="text" name="category" id="category-input" placeholder="Pick a category" readonly>
        </div>
        <div class="tutorial-input-container">
            <textarea class="tutorial-textarea" name="tutorial" placeholder="Tutorial">{{ old('tutorial') }}</textarea>
        </div>
        <div class="description-input-container">
            <textarea class="description-textarea" name="description" placeholder="Description">{{ old('description') }}</textarea>
        </div>
        <div class="media-upload-container">
            <p>Media upload here later!</p>
        </div>
        <div class="extra-options-container">
            <p>Extra options here!!</p>
            <div class="experience-visibility-input-container">
                <input type="text" name="visibility" id="experience-visibility-input" value="{{ old('visibility', 'Public') }}" placeholder="Choose visiblity" readonly>
            </div>
            <div></div>
        </div>

        @push('popup')
            <ul id="category-popup" class="category-popup">
                <li role="option" class="category-popup-options">Electronics</li>
                <li role="option" class="category-popup-options">Housekeeping</li>
                <li role="option" class="category-popup-options">Mechanics</li>
                <li role="option" class="category-popup-options">Woodwork</li>
                <li role="option" class="category-popup-options">Misc</li>
            </ul>
        @endpush
        @push('popup')
            <ul id="visibility-popup" class="visibility-popup">
                <li role="option" class="visibility-popup-options">Public</li>
                <li role="option" class="visibility-popup-options">Unlisted</li>
                <li role="option" class="visibility-popup-options">Private</li>
            </ul>
        @endpush
    </form> 
</x-main-layout>