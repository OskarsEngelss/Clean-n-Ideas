<x-main-layout title="Favourites">
    <h2>Favourites</h2>
    @forelse ($experiences as $experience)
        <x-tutorial-card
            :title="$experience->title"
            :description="$experience->description"
            :category="$experience->category"
            :thumbnail="$experience->thumbnail_url"
            :url="route('experience.show', $experience->slug)"
        ></x-tutorial-card>
    @empty
        <p>You haven't favourited any experiences yet</p>
    @endforelse
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
</x-main-layout>
