<x-main-layout title="Experience publisher">
    <form action="{{ route('experience.store') }}" class="experience-form" id="create-tutorial-form" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="publish-experience-button-container">
            <button>
                Publish
            </button>
        </div>
        <div class="title-input-container">
            <input value="{{ old('title') }}" class="title-input default-input-style" type="text" name="title" placeholder="Title"/>
        </div>
        <div class="category-input-container">
            <input value="{{ old('category') }}" type="text" name="category" id="category-input" class="default-input-style" placeholder="Pick a category" readonly data-popup-target="category-popup" data-option-selector=".category-popup-options">
        </div>
        <div class="tutorial-input-container">
            <input type="hidden" id="old-tutorial-content" value="{{ old('tutorial') }}">
            <div id="tutorial-editor" class="tutorial-textarea default-input-style" contenteditable="true"></div>
            <div id="editor-placeholder" class="editor-placeholder">Tutorial</div>
            <input type="hidden" name="tutorial" id="editorContent">
        </div>
        <div class="description-input-container">
            <textarea class="description-textarea default-input-style" name="description" placeholder="Description">{{ old('description') }}</textarea>
        </div>

        <x-experience-create-media-upload />

        <x-experience-create-extra-options-container />

        <x-experience-create-category-popup />
        <x-experience-create-visibility-popup />
        <x-add-outside-links-popup />
    </form>
    
    <x-upload-progress-popup />
</x-main-layout>