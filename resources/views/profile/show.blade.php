<x-main-layout>
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
                            <button type="submit">
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
                <img alt="Profile picture" src="https://static.vecteezy.com/system/resources/previews/013/360/247/non_2x/default-avatar-photo-icon-social-media-profile-sign-symbol-vector.jpg">
            </div>
        </div>
        <div class="profile-lower-grid">
            <p>lower grid!!</p>
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