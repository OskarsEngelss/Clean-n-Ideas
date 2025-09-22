<x-main-layout title="Your experiences">
            <h2>Your shared experiences</h2>
            @forelse ($experiences as $experience)
                <x-tutorial-card
                    :title="$experience->title"
                    :description="$experience->description"
                    :category="$experience->category"
                    :thumbnail="$experience->thumbnail_url"
                    :user="$experience->user"
                    :url="route('experience.show', $experience->slug)"
                ></x-tutorial-card>
            @empty
                <p>You haven't shared any experiences yet</p>
            @endforelse
            <div class="sorting-type-selection-container">
                Sort by:
                <a href="{{ route('your-experiences', ['order' => 'desc']) }}"
                class="order-option-button">
                    Newest First
                </a>
                <a href="{{ route('your-experiences', ['order' => 'asc']) }}"
                class="order-option-button">
                    Oldest First
                </a>
            </div>
            <div class="experience-create-button" id="experience-create-button" data-url="{{ route('experience.create') }}">
                Share new +
            </div>
</x-main-layout>