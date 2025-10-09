@push('popup')
    <div id="experience-show-description-popup" class="default-popup-style">
        <x-popup-close-component />
        <div>
            <h4>Description:</h4>
            <p>{{ $experience->description }}</p>
        </div>
    </div>
@endpush