<x-main-layout title="Experience publisher">
    <form action="{{ route('experience.store') }}" class="experience-form" id="create-tutorial-form" method="POST" enctype="multipart/form-data">
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
            <input value="{{ old('category') }}" type="text" name="category" id="category-input" placeholder="Pick a category" readonly id="category-input" data-popup-target="category-popup" data-option-selector=".category-popup-options">
        </div>
        <div class="tutorial-input-container">
            <input type="hidden" id="old-tutorial-content" value="{{ old('tutorial') }}">
            <div id="tutorial-editor" class="tutorial-textarea" contenteditable="true"></div>
            <div id="editor-placeholder" class="editor-placeholder">Tutorial</div>
            <input type="hidden" name="tutorial" id="editorContent">
        </div>
        <div class="description-input-container">
            <textarea class="description-textarea" name="description" placeholder="Description">{{ old('description') }}</textarea>
        </div>

        <x-experience-create-media-upload />

        <x-experience-create-extra-options-container />

        <x-experience-create-category-popup />
        <x-experience-create-visibility-popup />
    </form>

    @push('popup')
        <div id="upload-progress-popup">
            <div id="upload-progress-container" class="upload-progress-container">
                <p>Progress:</p>
                <div class="upload-progress-bar-container">
                    <div id="upload-progress"></div>
                    <div class="upload-progress-mold"></div>
                </div>
                <div id="upload-progress-time" class="upload-progress-time"></div>
            </div>
        </div>
    @endpush
    
</x-main-layout>