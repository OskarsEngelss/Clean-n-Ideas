@foreach($lists as $list)
    @php
        $saved = $list->tutorialListItems->contains('tutorial_id', $experienceId);
    @endphp

    <div class="experience-show-list-add-card">
        <div>
            <h3>{{ $list->name }}</h3>
        </div>
        <button class="experience-show-list-add-button {{ $saved ? 'saved' : '' }}" data-list-id="{{ $list->id }}" data-tutorial-id="{{ $experienceId }}">
            {{ $saved ? 'Remove' : 'Add' }}
        </button>
    </div>
@endforeach