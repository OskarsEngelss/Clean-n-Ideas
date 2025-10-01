<x-main-layout>
    <div class="two-to-one-grid">
        <section class="experience-show-container">
            <h2>{{ $experience->title }}</h2>
            <p>{{ $experience->tutorial }}</p>
        </section>
        <section class="experience-show-options">
            <a href="{{ route('profile.show', $experience->user->id) }}" class="experience-creator-info">
                <img atl="Profile icon" src="https://static.vecteezy.com/system/resources/previews/013/360/247/non_2x/default-avatar-photo-icon-social-media-profile-sign-symbol-vector.jpg" >
                <div>
                    <p>{{ $experience->user->name }}</p>
                    <p>Followers: {{ $followersCount }} </p>
                </div>
            </a>
            <div class="experience-show-save-rate-description-container">
                <form class="experience-show-favourite-form" action="{{ route('tutorialList.favourite.store')  }}" method="POST">
                    @csrf
                    <input type="hidden" name="experience_id" value="{{ $experience->id }}">
                    <button class="experience-show-favourite-button">
                        @if($favourited)
                            <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="var(--favourite-color)">
                                <path class="default" d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Z"/>    
                                <path class="hovered" d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="var(--favourite-color)">
                                <path class="default" d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z"/>
                                <path class="hovered" d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Z"/>
                            </svg>
                        @endif
                    </button>
                </form>
                <form class="experience-show-save-form">
                    <button class="experience-show-save-to-list-button">
                        <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="var(--text-color)">
                            <path d="M840-680v480q0 33-23.5 56.5T760-120H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h480l160 160ZM480-240q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35ZM240-560h360v-160H240v160Z"/>
                        </svg>
                    </button>
                </form>
                <div class="like-dislike-button-counter-container">
                    <button id="experience-show-tutorial-like-button">
                        <x-like-button-svg></x-like-button-svg>
                    </button>
                    <p>10k</p>
                </div>
                <div class="like-dislike-button-counter-container">
                    <button id="experience-show-tutorial-dislike-button">
                        <x-dislike-button-svg></x-dislike-button-svg>
                    </button>
                    <p>967</p>
                </div>
                <button id="experience-show-description-button" data-popup-target="experience-show-description-popup">Description</button>
            </div>
            <button id="experience-show-comments-button" data-popup-target="experience-show-comments-popup">Comments</button>
            <button id="experience-show-media-button" data-popup-target="experience-show-media-popup">Media</button>
        </section>
    </div>

    
    @push('popup')
        <div id="experience-show-description-popup">
            <div class="experience-show-popup-close-container">
                <button class="experience-show-popups-off-button">
                    <svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="var(--text-color)">
                        <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
                    </svg>
                </button>
            </div>
            <div>
                <h4>Description:</h4>
                <p>{{ $experience->description }}</p>
            </div>
        </div>
    @endpush
    @push('popup')
        <div id="experience-show-comments-popup">
            <div class="experience-show-popup-close-container">
                <button class="experience-show-popups-off-button">
                    <svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="var(--text-color)">
                        <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
                    </svg>
                </button>
            </div>
            @forelse($comments as $comment)
                <x-comment-component
                    :comment="$comment"
                ></x-comment-component>
            @empty
                <p>No comments</p>
            @endforelse
            @if(Auth::user())
                <form action="{{ route('comment.store') }}" id="experience-show-comments-popup-form" method="POST">
                    @csrf
                    <input type="hidden" name="tutorial_id" value="{{ $experience->id }}">
                    <input type="hidden" name="parent_id" id="parent_id" value="">

                    <div id="experience-show-comments-popup-button-container">
                        <button type="button" id="experience-show-comments-popup-cancel-button">Cancel</button>
                        <button type="button" id="experience-show-comments-popup-emoji-button">😊</button>
                        <button id="experience-show-comments-popup-submit-button">Comment</button>
                    </div>
                    <textarea name="content" id="experience-show-comments-popup-input" rows="1" placeholder="Comment..."></textarea>
                    <emoji-picker id="experience-show-comments-popup-emoji-picker" style="display:none;"></emoji-picker> <!-- The list of pickable emojis -->
                </form>
            @else
                <a class="experience-show-comments-popup-form-denied" href="/login">
                    <div>
                        <p><span>Sign in</span> to comment/reply</p>
                    </div>
                </a>
            @endif
        </div>
    @endpush
    @push('popup')
        <div id="experience-show-media-popup">
            <div class="experience-show-popup-close-container">
                <button class="experience-show-popups-off-button">
                    <svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="var(--text-color)">
                        <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
                    </svg>
                </button>
            </div>
            <div>
                <p>Media</p>
            </div>
        </div>
    @endpush
</x-main-layout>