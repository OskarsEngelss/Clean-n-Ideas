<div class="experience-show-reply-container">
    <a href="{{ route('profile.show', $reply->user) }}" class="experience-show-comment-profile-picture-username-followers">
        <img alt="Profile icon" src="{{ $reply->user->profile_picture ? asset('storage/' . $reply->user->profile_picture) : asset('images/defaults/default-profile-picture.jpg') }}">
        <div class="experience-show-comment-username-followers">
            <h4>{{ $reply->user->name }}</h4>
            <p>Followers: {{ $reply->user->followers()->count() }}</p>
        </div>
    </a>
    <div class="experience-show-comment-like-dislike">
        <div class="like-dislike-button-counter-container">
            <form action="{{ route('comment.react') }}" class="experience-show-comment-reaction-form" data-type="like" data-comment-id="{{ $reply->id }}">
                @csrf
                <button id="experience-show-comment-like-button" class="{{ $reply->userReaction && $reply->userReaction->type === 'like' ? 'active' : '' }}">
                    <x-like-button-svg></x-like-button-svg>
                </button>
            </form>
            <p id="like-count-{{ $reply->id }}">{{ $reply->likes_count }}</p>
        </div>
        <div class="like-dislike-button-counter-container">
            <form action="{{ route('comment.react') }}" class="experience-show-comment-reaction-form" data-type="dislike" data-comment-id="{{ $reply->id }}">
                @csrf
                <button id="experience-show-comment-dislike-button" class="{{ $reply->userReaction && $reply->userReaction->type === 'dislike' ? 'active' : '' }}">
                    <x-dislike-button-svg></x-dislike-button-svg>
                </button>
            </form>
            <p id="dislike-count-{{ $reply->id }}">{{ $reply->dislikes_count }}</p>
        </div>
    </div>
    <p class="experience-show-comment-time-posted">Posted: {{ $reply->created_at->diffForHumans() }}</p>
    <p class="experience-show-comment">{{ $reply->content }}</p>
</div>