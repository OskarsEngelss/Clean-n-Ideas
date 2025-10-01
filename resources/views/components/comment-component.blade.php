<div class="experience-show-comment-container">
    <a href="/" class="experience-show-comment-profile-picture-username-followers">
        <img src="https://static.vecteezy.com/system/resources/previews/013/360/247/non_2x/default-avatar-photo-icon-social-media-profile-sign-symbol-vector.jpg" alt="Profile icon">
        <div class="experience-show-comment-username-followers">
            <h4>{{ $comment->user->name }}</h4>
            <p>Followers: {{ $comment->user->followers()->count() }}</p>
        </div>
    </a>
    <div class="experience-show-comment-like-dislike">
        <div class="like-dislike-button-counter-container">
            <button id="experience-show-comment-like-button">
                <x-like-button-svg></x-like-button-svg>
            </button>
            <p>300k</p>
        </div>
        <div class="like-dislike-button-counter-container">
            <button id="experience-show-comment-dislike-button">
                <x-dislike-button-svg></x-dislike-button-svg>
            </button>
            <p>100</p>
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
        @php
            $replyCount = $comment->replies->count();
            $replyLabel = $replyCount === 1 ? '1 Reply' : "$replyCount replies";
        @endphp
        <button class="experience-show-comment-toggle-reply-container-button" data-reply-label="{{ $replyLabel }}">
            {{ $replyLabel }}
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