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
                <x-experience-show-favourite-form :experience="$experience" :favourited="$favourited" />
                <div class="experience-show-lists-button-container">
                    <button class="experience-show-lists-button" data-popup-target="experience-show-lists-popup">
                        <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="var(--text-color)">
                            <path d="M840-680v480q0 33-23.5 56.5T760-120H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h480l160 160ZM480-240q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35ZM240-560h360v-160H240v160Z"/>
                        </svg>
                    </button>
                </div>
                <div class="like-dislike-button-counter-container">
                    <form action="{{ route('experience.react') }}" class="experience-show-tutorial-reaction-form" data-type="like" data-tutorial-id="{{ $experience->id }}">
                        @csrf
                        <button id="experience-show-tutorial-like-button" class="{{ $experience->userReaction && $experience->userReaction->type === 'like' ? 'active' : '' }}">
                            <x-like-button-svg></x-like-button-svg>
                        </button>
                    </form>
                    <p id="tutorial-like-count-{{ $experience->id }}">{{ $experience->likes()->count() }}</p>
                </div>
                <div class="like-dislike-button-counter-container">
                    <form action="{{ route('experience.react') }}" class="experience-show-tutorial-reaction-form" data-type="dislike" data-tutorial-id="{{ $experience->id }}">
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
                @if(Auth::user()->id == $experience->user->id)
                    <button id="experience-delete-button" data-popup-target="experience-delete-popup" class="default-experience-show-popup-button-style">Delete experience</button>
                @endif
            @endif
        </section>
    </div>

    <x-experience-show-description-popup :experience="$experience" />
    <x-experience-show-comments-popup :experience="$experience" :comments="$comments" />
    <x-experience-show-media-popup :experience="$experience" />
    <x-experience-show-links-popup :experience="$experience" />
    <x-experience-delete-popup :experience="$experience" />

    @push('popup')
        <div id="experience-show-lists-popup" class="default-popup-style">
            <x-popup-close-component />
            <div>
                All of your lists:
            </div>
        </div>
    @endpush
</x-main-layout>