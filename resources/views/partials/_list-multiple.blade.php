@foreach($lists as $list)
    <div class="list-card">
        <a href="{{ route('list.show', ['id' => Auth::user()->id, 'list_id' => $list->id]) }}">
            <div>
                <div class="list-card-text-container">
                    <h3>{{ $list->name }}</h3>
                    <p>Tutorials: {{ $list->tutorial_list_items_count }}</p>
                </div>
            </div>
        </a>
    </div>
@endforeach