<x-main-layout title="Lists">
    <div class="two-to-one-grid">
        <section class="lists" data-auth-user-id="{{ auth()->id() }}">
            @forelse($lists as $list)
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
            @empty
                <p>Currently you have no lists</p>
            @endforelse
        </section>
        <section class="options">
            <h2>Lists</h2>
            <button id="lists-make-new-list-button" data-popup-target="lists-make-new-list-popup">Make a list</button>
        </section>
    </div>

    @push('popup')
        <div id="lists-make-new-list-popup" class="default-popup-style">
            <x-popup-close-component />
            <div>
                <h4>Make a new list</h4>
                <form action="{{ route('list.storeList') }}" method="POST" id="new-list-form">
                    @csrf

                    <input id="list-name-input" name="name" placeholder="List name" class="default-input-style" />
                    <label>
                        Public:
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox" name="is_public" value="1">
                    </label>
                    <button type="submit">Create</button>
                </form>
            </div>
        </div>
    @endpush
</x-main-layout>