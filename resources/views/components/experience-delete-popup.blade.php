@push('popup')
    <div id="experience-delete-popup" class="default-popup-style">
        <x-popup-close-component />
        <div class="experiencec-delete-popup-content">
            <h4>Delete experience:</h4>
            <p>{{ $experience->title }}</p>
            <form action="{{ route('experience.destroy', $experience->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <input name="title_confirmation" type="text" placeholder="Enter full title" class="default-input-style">
                <button type="submit" onclick="return confirm('Are you sure? This cannot be undone!')">Delete</button>
            </form>
        </div>
    </div>
@endpush