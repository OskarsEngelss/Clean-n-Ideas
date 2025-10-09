<div class="experience-show-comment-container" data-comment="{{ $comment->id }}">
    <a href="{{ route('profile.show', $comment->user) }}" class="experience-show-comment-profile-picture-username-followers">
        <img alt="Profile icon" src="{{ $comment->user->profile_picture ? asset('storage/' . $comment->user->profile_picture) : asset('images/defaults/default-profile-picture.jpg') }}">
        <div class="experience-show-comment-username-followers">
            <h4>{{ $comment->user->name }}</h4>
            <p>Followers: {{ $comment->user->followers()->count() }}</p>
        </div>
    </a>
    <div class="experience-show-comment-like-dislike">
        <div class="like-dislike-button-counter-container">
            <form action="{{ route('comment.react') }}" class="experience-show-comment-reaction-form" data-type="like" data-comment-id="{{ $comment->id }}">
                @csrf
                <button id="experience-show-comment-like-button" class="{{ $comment->userReaction && $comment->userReaction->type === 'like' ? 'active' : '' }}">
                    <x-like-button-svg></x-like-button-svg>
                </button>
            </form>
            <p id="like-count-{{ $comment->id }}">{{ $comment->likes_count }}</p>
        </div>
        <div class="like-dislike-button-counter-container">
            <form action="{{ route('comment.react') }}" class="experience-show-comment-reaction-form" data-type="dislike" data-comment-id="{{ $comment->id }}">
                @csrf
                <button id="experience-show-comment-dislike-button" class="{{ $comment->userReaction && $comment->userReaction->type === 'dislike' ? 'active' : '' }}">
                    <x-dislike-button-svg></x-dislike-button-svg>
                </button>
            </form>
            <p id="dislike-count-{{ $comment->id }}">{{ $comment->dislikes_count }}</p>
        </div>
    </div>
    <button class="experience-show-comment-reply" data-comment-id="{{ $comment->id }}">
        <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="var(--text-color)">
            <path d="M760-200v-160q0-50-35-85t-85-35H273l144 144-57 56-240-240 240-240 57 56-144 144h367q83 0 141.5 58.5T840-360v160h-80Z"/>
        </svg>
    </button>
    <p class="experience-show-comment-time-posted">Posted: {{ $comment->created_at->diffForHumans() }}</p>
    <p class="experience-show-comment">{{ $comment->content }}</p>
</div>

@if($comment->replies->count())
    <div class="experience-show-comment-reply-and-toggle-container">
        <button class="experience-show-comment-toggle-reply-container-button" data-reply-count="{{ $comment->replies->count() }}">
            {{ $comment->replies->count() }} {{ $comment->replies->count() === 1 ? 'Reply' : 'Replies' }}
        </button>

        <div class="experience-show-comment-reply-container">
            <div>
                @foreach($comment->replies as $reply)
                    <x-reply-component
                        :reply="$reply"
                    ></x-reply-component>
                @endforeach
            </div>
        </div>
    </div>
@endif