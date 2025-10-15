@push('popup')
    <div id="experience-show-lists-popup" class="default-popup-style">
        <x-popup-close-component />
        <div>
            <form action="{{ route('list.storeList') }}" method="POST" id="new-list-form"  data-tutorial-id="{{ $experienceId }}">
                @csrf

                <input id="list-name-input" name="name" placeholder="List name" class="default-input-style" required />
                <label>
                    Public:
                    <input type="hidden" name="is_public" value="0">
                    <input type="checkbox" name="is_public" value="1">
                </label>
                <button type="submit">Create</button>
            </form>
        </div>
        <div class="experience-show-list-option-container" data-experience-slug="{{ $experienceSlug }}"  data-experience-id="{{ $experienceId }}">
            @forelse($lists as $list)
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
            @empty
                <p>Currently you have no lists</p>
            @endforelse
        </div>
        <div class="experience-show-list-load-more-trigger"></div>
    </div>
@endpush