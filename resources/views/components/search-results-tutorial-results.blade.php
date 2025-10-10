<div class="search-result-experiences-container">
    @if($experiences->items->isEmpty())
        <p class="search-result-container-info-no-experiences">No experiences found.</p>
    @else
        @foreach($experiences->items as $experience)
            <div>
                <x-tutorial-card
                    :experience="$experience"
                    :thumbnail="$experience->thumbnail_url"
                    :user="$experience->user"
                    :savedCount="$experience->tutorial_list_items_count"
                    :url="route('experience.show', $experience->slug)"
                ></x-tutorial-card>
            </div>
        @endforeach
    @endif
</div>
@if($experiences->hasmore)
    <button class="search-result-load-more-button" data-type="experiences" data-offset="6">Load More Experiences</button>
@endif