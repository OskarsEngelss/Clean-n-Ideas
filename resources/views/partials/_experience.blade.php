@foreach($experiences as $experience)
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