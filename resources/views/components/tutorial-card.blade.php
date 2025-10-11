<a href="{{ $url }}" class="tutorial-card-link">
    <div class="tutorial-card">
        <img class="thumbnail-preview" src="{{ url($experience->thumbnail) }}" alt="{{ $experience->category }} thumbnail">
        <div class="tutorial-card-text-container">
            <h3 class="tutorial-card-title">{{ $experience->title }}</h3>
            <div class="tutorial-card-user-info-container">
                <img class="tutorial-card-profile-picture" atl="Profile icon" src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/defaults/default-profile-picture.jpg') }}" >
                <p class="tutorial-card-category">{{ $user->name }}</p>
            </div>
            <p class="tutorial-card-favourites">Likes: {{ $experience->likes()->count() }} | Dislikes: {{ $experience->dislikes()->count() }}</p>
            <p class="tutorial-card-upload-time">{{ $experience->created_at->diffForHumans() }}</p>
        </div>
    </div>
</a>