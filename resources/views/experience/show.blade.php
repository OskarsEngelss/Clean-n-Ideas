<x-main-layout title="{{ $experience->title }}">
    <div class="two-to-one-grid">
        <section class="experience-show-container">
            <h2>{{ $experience->title }}</h2>
            <div>
                {!! $experience->tutorial !!}
            </div>
        </section>
        <section class="experience-show-options">
            <a href="{{ route('profile.show', $experience->user->id) }}" class="experience-creator-info">
                <img atl="Profile icon" src="{{ $experience->user->profile_picture ? asset('storage/' . $experience->user->profile_picture) : asset('images/defaults/default-profile-picture.jpg') }}">
                <div>
                    <p>{{ $experience->user->name }}</p>
                    <p>Followers: {{ $followersCount }} </p>
                </div>
            </a>
            <div class="experience-show-save-rate-description-container">
                <x-experience-show-favourite-button :experienceId="$experience->id" :favourited="$favourited" :favouritesListId="$favouritesListId"/>
                <x-experience-show-lists-button />
                <div class="like-dislike-button-counter-container">
                    <form method="POST" action="{{ route('experience.react') }}" class="experience-show-tutorial-reaction-form" data-type="like" data-tutorial-id="{{ $experience->id }}">
                        @csrf
                        <button id="experience-show-tutorial-like-button" class="{{ $experience->userReaction && $experience->userReaction->type === 'like' ? 'active' : '' }}">
                            <x-like-button-svg></x-like-button-svg>
                        </button>
                    </form>
                    <p id="tutorial-like-count-{{ $experience->id }}">{{ $experience->likes()->count() }}</p>
                </div>
                <div class="like-dislike-button-counter-container">
                    <form method="POST" action="{{ route('experience.react') }}" class="experience-show-tutorial-reaction-form" data-type="dislike" data-tutorial-id="{{ $experience->id }}">
                        @csrf
                        <button id="experience-show-tutorial-dislike-button" class="{{ $experience->userReaction && $experience->userReaction->type === 'dislike' ? 'active' : '' }}">
                            <x-dislike-button-svg></x-dislike-button-svg>
                        </button>
                    </form>
                    <p id="tutorial-dislike-count-{{ $experience->id }}">{{ $experience->dislikes()->count() }}</p>
                </div>
                <button id="experience-show-description-button" data-popup-target="experience-show-description-popup" class="default-experience-show-popup-button-style">Description</button>
            </div>
            <button id="experience-show-comments-button" data-popup-target="experience-show-comments-popup" class="default-experience-show-popup-button-style">Comments</button>
            <button id="experience-show-media-button" data-popup-target="experience-show-media-popup" class="default-experience-show-popup-button-style">Media</button>
            <button id="experience-show-links-button" data-popup-target="experience-show-links-popup" class="default-experience-show-popup-button-style">Links</button>
            @if(Auth::user())
                @if(Auth::user()->id == $experience->user->id || Auth::user()->role == "admin")
                    <button id="experience-delete-button" data-popup-target="experience-delete-popup" class="default-experience-show-popup-button-style">Delete experience</button>
                @endif
            @endif
        </section>
    </div>


    <!-- Every Popup Component --> <!-- Every Popup Component --> <!-- Every Popup Component --> <!-- Every Popup Component -->
    <!-- Every Popup Component --> <!-- Every Popup Component --> <!-- Every Popup Component --> <!-- Every Popup Component -->
    <x-experience-show-description-popup :experience="$experience" />
    <x-experience-show-comments-popup :experience="$experience" :comments="$comments" />
    <x-experience-show-media-popup :experience="$experience" />
    <x-experience-show-links-popup :experience="$experience" />
    <x-experience-delete-popup :experience="$experience" />
    @if(Auth::check())
        <x-experience-show-lists-popup :lists="$lists" :experienceId="$experience->id" :experienceSlug="$experience->slug" />
    @endif
</x-main-layout>