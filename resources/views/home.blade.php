<x-main-layout title="Home">
    <div class="home-container">
        @forelse ($experiences as $experience)
            @if ($experience->visibility == "Public")
                <div>
                    <x-tutorial-card
                        :experience="$experience"
                        :thumbnail="$experience->thumbnail_url"
                        :user="$experience->user"
                        :savedCount="$experience->tutorial_list_items_count"
                        :url="route('experience.show', $experience->slug)"
                    ></x-tutorial-card>
                </div>
            @endif
        @empty
            <p>Nothing is posted?! How?!</p>
        @endforelse
    </div>
</x-main-layout>