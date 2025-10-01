<x-main-layout title="Your experiences">
    <div class="two-to-one-grid">
        <section class="your-experiences-content">
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
                <p class="your-experiences-info-no-experiences">You haven't shared any experiences yet</p>
            @endforelse
        </section>
        <section class="your-experiences-options">
            <h2>Your shared experiences</h2>
            <!-- <div class="sorting-type-selection-container">
                Sort by:
                <a href="{{ route('your-experiences', ['order' => 'desc']) }}"
                class="order-option-button">
                    Newest First
                </a>
                <a href="{{ route('your-experiences', ['order' => 'asc']) }}"
                class="order-option-button">
                    Oldest First
                </a>
            </div> -->
            <a class="your-experiences-share-new-button" href="{{ route('experience.create') }}">Share new +</a>
        </section>
    </div>
</x-main-layout>