@push('popup')
    <div id="experience-show-media-popup">
        <div class="experience-show-popup-close-container">
            <button class="experience-show-popups-off-button">
                <svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="var(--text-color)">
                    <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
                </svg>
            </button>
        </div>
        <div>
            @if ($experience->media->count() > 0)
                @foreach ($experience->media as $media)
                    @if ($media->type === 'image')
                        <img class="experience-show-media-popup-image" src="{{ asset('storage/' . $media->path) }}" alt="Media">
                    @elseif ($media->type === 'video')
                        <video controls class="experience-show-media-popup-video">
                            <source src="{{ asset('storage/' . $media->path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                @endforeach
            @else
                <p>No media available</p>
            @endif
        </div>
    </div>
@endpush