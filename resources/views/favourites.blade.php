<x-main-layout title="Favourites">
    <div class="two-to-one-grid">
        <section>
            <h2>Favourites</h2>
            @forelse ($experiences as $experience)
                <x-tutorial-card
                    :experience="$experience"
                    :thumbnail="$experience->thumbnail_url"
                    :user="$experience->user"
                    :savedCount="$experience->tutorial_list_items_count"
                    :url="route('experience.show', $experience->slug)"
                ></x-tutorial-card>
            @empty
                <p>You haven't favourited any experiences yet</p>
            @endforelse
        </section>
        <section>
            <div class="sorting-type-selection-container">
                <a href="{{ route('favourites', ['order' => 'desc']) }}"
                class="order-option-button">
                    Newest First
                </a>
                <a href="{{ route('favourites', ['order' => 'asc']) }}"
                class="order-option-button">
                    Oldest First
                </a>
            </div>
        </section>
    </div>
</x-main-layout>
