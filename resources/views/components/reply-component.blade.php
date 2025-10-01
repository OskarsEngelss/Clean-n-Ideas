<div class="experience-show-reply-container">
    <a href="/" class="experience-show-comment-profile-picture-username-followers">
        <img src="https://static.vecteezy.com/system/resources/previews/013/360/247/non_2x/default-avatar-photo-icon-social-media-profile-sign-symbol-vector.jpg" alt="Profile icon">
        <div class="experience-show-comment-username-followers">
            <h4>{{ $reply->user->name }}</h4>
            <p>Followers: {{ $reply->user->followers()->count() }}</p>
        </div>
    </a>
    <div class="experience-show-comment-like-dislike">
        <div class="like-dislike-button-counter-container">
            <button id="experience-show-comment-like-button">
                <x-like-button-svg></x-like-button-svg>
            </button>
            <p>1000k</p>
        </div>
        <div class="like-dislike-button-counter-container">
            <button id="experience-show-comment-dislike-button">
                <x-dislike-button-svg></x-dislike-button-svg>
            </button>
            <p>967</p>
        </div>
    </div>
    <p class="experience-show-comment-time-posted">Posted: {{ $reply->created_at->diffForHumans() }}</p>
    <p class="experience-show-comment">{{ $reply->content }}</p>
</div>