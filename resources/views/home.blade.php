<x-main-layout title="Home">
    <div class="home-container">
        <div class="carousel">
            <button class="carousel-back-button"><</button>

            <div class="carousel-group">
                @forelse ($experiences as $experience)
                    @if ($experience->visibility == "Public")
                        <x-tutorial-card
                            :experience="$experience"
                            :thumbnail="$experience->thumbnail_url"
                            :user="$experience->user"
                            :savedCount="$experience->tutorial_list_items_count"
                            :url="route('experience.show', $experience->slug)"
                        ></x-tutorial-card>
                    @endif
                @empty
                    <p>Nothing is posted?! How?!</p>
                @endforelse
            </div>

            <button class="carousel-forward-button">></button>
        </div>
    </div>
</x-main-layout>