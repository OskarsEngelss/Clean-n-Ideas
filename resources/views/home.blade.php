<x-main-layout title="Home">
    @forelse ($experiences as $experience)
        <x-tutorial-card
            :experience="$experience"
            :thumbnail="$experience->thumbnail_url"
            :user="$experience->user"
            :savedCount="$experience->tutorial_list_items_count"
            :url="route('experience.show', $experience->slug)"
        ></x-tutorial-card>
    @empty
        <p>You haven't shared any experiences yet</p>
    @endforelse
</x-main-layout>