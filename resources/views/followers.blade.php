<x-main-layout title="Following">
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
                        <img alt="Profile icon" src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/defaults/default-profile-picture.jpg') }}">
                    </div>
                </a>
            @empty
                <p>Currently not following anyone</p>
            @endforelse
        </section>
        <section class="options">
            <h2>Following</h2>
        </section>
    </div>
</x-main-layout>