<x-main-layout title="{{ $list->name }}">
    <div class="two-to-one-grid">
        <section class="list-show-content">
            @forelse($experiences as $experience)
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
                @if($list->is_favourite)
                    <p class="list-show-info-no-experiences">You haven't favourited any experiences yet</p>
                @else
                    <p>This list doesn't contain any tutorials</p>
                @endif
            @endforelse
        </section>
        <section class="list-show-options">
            <h2>{{ $list->name }}</h2>
        </section>
    </div>
</x-main-layout>
