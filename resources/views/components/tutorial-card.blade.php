<a href="{{ $url }}" class="tutorial-card-link">
    <div class="tutorial-card">
        <img class="thumbnail-preview" src="https://www.jakob-persson.com/media/posts/41/un4jeep.png" alt="Preview picture">
        <div class="tutorial-card-text-container">
            <h3 class="tutorial-card-title">{{ $experience->title }}</h3>
            <!-- <p class="tutorial-card-description">{{ $experience->description }}</p> -->
            <div class="tutorial-card-user-info-container">
                <img class="tutorial-card-profile-picture" atl="Profile icon" src="https://static.vecteezy.com/system/resources/previews/013/360/247/non_2x/default-avatar-photo-icon-social-media-profile-sign-symbol-vector.jpg" >
                <p class="tutorial-card-category">{{ $user->name }}</p>
            </div>
            <p class="tutorial-card-favourites">Favourites: {{ $savedCount }}</p>
            <p class="tutorial-card-upload-time">{{ $experience->created_at->diffForHumans() }}</p>
        </div>
    </div>
</a>