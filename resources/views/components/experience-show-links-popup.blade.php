@push('popup')
    <div id="experience-show-links-popup" class="default-popup-style">
        <x-popup-close-component />
        <div class="experience-show-links-popup-links-container">
            @forelse($experience->links as $link)
                <a href="{{ $link->url }}" target="_blank">{{ $link->url }}</a>
            @empty
                <p>No links available</p>
            @endforelse
        </div>
    </div>
@endpush