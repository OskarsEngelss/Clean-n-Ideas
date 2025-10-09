@push('popup')
    <div id="experience-show-media-popup" class="default-popup-style">
        <x-popup-close-component />
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