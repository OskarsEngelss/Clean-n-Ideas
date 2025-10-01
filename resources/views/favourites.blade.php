<x-main-layout title="Favourites">
    <div class="two-to-one-grid">
        <section class="favourites-content">
            @forelse ($experiences as $experience)
                <div>
                    <x-tutorial-card
                        :experience="$experience"
                        :thumbnail="$experience->thumbnail_url"
                        :user="$experience->user"
                        :savedCount="$experience->tutorial_list_items_count"
                        :url="route('experience.show', $experience->slug)"
                    ></x-tutorial-card>
                </div>
            @empty
                <p class="favourites-info-no-experiences">You haven't favourited any experiences yet</p>
            @endforelse
        </section>
        <section class="favourites-options">
            <h2>Favourites</h2>
            <!-- <div class="sorting-type-selection-container">
                <a href="{{ route('favourites', ['order' => 'desc']) }}"
                class="order-option-button">
                    Newest First
                </a>
                <a href="{{ route('favourites', ['order' => 'asc']) }}"
                class="order-option-button">
                    Oldest First
                </a>
            </div> -->
        </section>
    </div>
</x-main-layout>
