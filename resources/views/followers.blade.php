<x-main-layout>
    <div class="two-to-one-grid">
        <section class="followers">
            @forelse($following as $user)
                <a class="follower-card" href="{{ route('profile.show', $user) }}">
                    <h3>{{ $user->name }}</h3>
                    @if($user->description)
                        <p>{{ $user->description }}</p>
                    @else
                        <p>No about available.</p>
                    @endif
                    <div class="follower-card-icon-container">
                        <img alt="Profile icon" src="https://static.vecteezy.com/system/resources/previews/013/360/247/non_2x/default-avatar-photo-icon-social-media-profile-sign-symbol-vector.jpg">
                    </div>
                </a>
            @empty
                <p>Currently not following anyone</p>
            @endforelse
        </section>
        <section class="options">
            <h2>Following</h2>
            <p>Sort coming later</p>
        </section>
    </div>
</x-main-layout>