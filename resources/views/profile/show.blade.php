<x-main-layout title="{{ auth()->user()->name }}">
    <div class="whole-profile-container">
        <div class="profile-upper-grid">
            <div class="username-container">
                <h2>{{ $user->name }}</h2>
            </div>
            <div class="follow-container">
                <div>
                    @if(Auth::user())
                        <form class="follow-user-form" method="POST" action="{{ route('follow.toggle', $user) }}">
                            @csrf
                            <button type="submit" @disabled(Auth::check() && Auth::user()->id == $user->id)>
                                {{ auth()->user()->following->contains($user->id) ? 'Unfollow' : 'Follow' }}
                            </button>
                        </form>
                    @endif
                    <p>{{ $followersCount }} followers</p>
                    <button id="profile-more-info-button">
                        More
                    </button>
                </div>
            </div>
            <div class="profile-picture-container">
                <img alt="Profile picture" src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/defaults/default-profile-picture.jpg') }}">
            </div>
        </div>
        <div class="profile-lower-grid">
            <div class="profile-experience-container">
                @foreach($experiences as $experience)
                    <div>
                        <x-tutorial-card
                            :experience="$experience"
                            :thumbnail="$experience->thumbnail_url"
                            :user="$experience->user"
                            :savedCount="$experience->tutorial_list_items_count"
                            :url="route('experience.show', $experience->slug)"
                        ></x-tutorial-card>
                    </div>
                @endforeach
            </div>
            <div id="user-load-more-trigger" data-user-id="{{ $user->id }}"></div>
        </div>
    </div>

    <!-- More info popup -->
    @push('popup')
        <div class="profile-popup-content">
            <button class="toggle-more-info-off">
                <svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="var(--text-color)">
                    <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
                </svg>
            </button>

            <div>
                <h4>About:</h4>
                @if($user->description)
                    <p>{{ $user->description }}</p>
                @else
                    <p>No about available.</p>
                @endif
            </div>
            <div>
                <p>Joined: {{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
    @endpush
</x-main-layout>