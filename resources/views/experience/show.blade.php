<x-main-layout>
    <h2>{{ $experience->title }}</h2>
    <div class="tutorial-show-tutorial-text-container">
        <p>{{ $experience->tutorial }}</p>
    </div>

    <p>{{ $experience->description }}</p>
    <form action="{{ route('tutorialList.favourite.store')  }}" method="POST">
        @csrf
        <input type="hidden" name="experience_id" value="{{ $experience->id }}">
        <button>Favourite</button>
    </form>
</x-main-layout>