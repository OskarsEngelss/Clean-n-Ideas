<nav>
    <div class="nav-logo-container">
        <a href="/">
            <h1>Clean n Ideas</h1>
        </a>
    </div>
    <button id="sidebar-toggle-button">
        <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="var(--text-color)">
            <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/>
        </svg>
        <svg class="close-icon hidden" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="var(--text-color)">
            <path d="M120-240v-80h520v80H120Zm664-40L584-480l200-200 56 56-144 144 144 144-56 56ZM120-440v-80h400v80H120Zm0-200v-80h520v80H120Z"/>
        </svg>
    </button>


    <form id="search-form" action="{{ route('search') }}" method="GET">
        <button id="search-toggle-off-button" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="var(--text-color)">
                <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
            </svg>
        </button>
        <input id="search-form-input" name="q" placeholder="Search...">
        <button id="search-form-button" type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="var(--text-color)">
                <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/>
            </svg>
        </button>
    </form>
    <div class="small-search-button-container">
        <button id="small-search-button">
            <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="var(--text-color)">
                <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/>
            </svg>
        </button>
    </div>


    @if (Auth::check())
        <div class="nav-profile">
            <button id="profile-icon">
                <img alt="Profile icon" src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/defaults/default-profile-picture.jpg') }}">
            </button>
        </div>
    @else
        <div class="nav-profile">
            <a href="/login" class="sign-in-button">
                Sign in
            </a>
        </div>
    @endif
</nav>