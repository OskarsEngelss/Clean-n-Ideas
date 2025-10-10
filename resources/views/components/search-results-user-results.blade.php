<div class="search-result-users-container">
    @if($users->items->isEmpty())
        <p class="search-result-container-info-no-experiences">No users found.</p>
    @else
        @foreach($users->items as $user)
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
        @endforeach
    @endif
</div>
@if($users->hasmore)
    <button class="search-result-load-more-button" data-type="users" data-offset="4">Load More Users</button>
@endif