<x-main-layout title="{{ $list->name }}">
    <div class="two-to-one-grid">
        <section class="your-experiences-content">
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
                    <p class="your-experiences-info-no-experiences">You haven't favourited any experiences yet</p>
                @else
                    <p class="your-experiences-info-no-experiences">This list doesn't contain any tutorials</p>
                @endif
            @endforelse
        </section>
        <section class="options">
            <h2>{{ $list->name }}</h2>
            @if(Auth::user()->id == $list->user_id && !$list->is_favourite)
                <form action="{{ route('list.destroy', $list->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button class="lists-show-delete-list-button" type="submit" onclick="return confirm('Are you sure you want to delete this list? This can not be undone')">
                        Delete list
                    </button>
                </form>
            @endif
        </section>
    </div>
</x-main-layout>
