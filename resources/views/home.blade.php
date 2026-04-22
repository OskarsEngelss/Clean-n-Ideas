<x-main-layout title="Home">
    <div class="home-container">
        @forelse ($experiences as $experience)
            <div>
                <x-tutorial-card
                    :experience="$experience"
                    :thumbnail="$experience->thumbnail_url"
                    :user="$experience->user"
                    :savedCount="$experience->tutorial_list_items_count"
                    :url="route('experience.show', $experience->slug)"
                ></x-tutorial-card>
            </div>
        @empty
            <p>Nothing is posted?! How?!</p>
        @endforelse
    </div>

    @push('popup')
        <div id="home-welcome-popup"
            data-wide-images='["{{ asset('images/home-welcome-tutorial/wide-tutorial-home-buttons.jpg') }}", "{{ asset('images/home-welcome-tutorial/wide-tutorial-list-buttons.jpg') }}", "{{ asset('images/home-welcome-tutorial/wide-tutorial-your-experiences.jpg') }}", "{{ asset('images/home-welcome-tutorial/wide-tutorial-following.jpg') }}", "{{ asset('images/home-welcome-tutorial/wide-tutorial-about-us.jpg') }}", "{{ asset('images/home-welcome-tutorial/wide-tutorial-main-feed.jpg') }}", "{{ asset('images/home-welcome-tutorial/wide-tutorial-search-bar.jpg') }}", "{{ asset('images/home-welcome-tutorial/wide-tutorial-sign-in.jpg') }}"]'
            data-medium-images='["{{ asset('images/home-welcome-tutorial/medium-tutorial-sidebar-open.jpg') }}", "{{ asset('images/home-welcome-tutorial/medium-tutorial-home-button.jpg') }}", "{{ asset('images/home-welcome-tutorial/medium-tutorial-list-buttons.jpg') }}", "{{ asset('images/home-welcome-tutorial/medium-tutorial-your-experiences.jpg') }}", "{{ asset('images/home-welcome-tutorial/medium-tutorial-following.jpg') }}", "{{ asset('images/home-welcome-tutorial/medium-tutorial-about-us.jpg') }}", "{{ asset('images/home-welcome-tutorial/medium-tutorial-sidebar-close.jpg') }}", "{{ asset('images/home-welcome-tutorial/medium-tutorial-main-feed.jpg') }}", "{{ asset('images/home-welcome-tutorial/medium-tutorial-search-bar.jpg') }}", "{{ asset('images/home-welcome-tutorial/medium-tutorial-sign-in.jpg') }}"]'
            data-narrow-images='["{{ asset('images/home-welcome-tutorial/narrow-tutorial-sidebar-open.jpg') }}", "{{ asset('images/home-welcome-tutorial/narrow-tutorial-home-button.jpg') }}", "{{ asset('images/home-welcome-tutorial/narrow-tutorial-list-buttons.jpg') }}", "{{ asset('images/home-welcome-tutorial/narrow-tutorial-your-experiences.jpg') }}", "{{ asset('images/home-welcome-tutorial/narrow-tutorial-following.jpg') }}", "{{ asset('images/home-welcome-tutorial/narrow-tutorial-about-us.jpg') }}", "{{ asset('images/home-welcome-tutorial/narrow-tutorial-sidebar-close.jpg') }}", "{{ asset('images/home-welcome-tutorial/narrow-tutorial-main-feed.jpg') }}", "{{ asset('images/home-welcome-tutorial/narrow-tutorial-search-open.jpg') }}", "{{ asset('images/home-welcome-tutorial/narrow-tutorial-search-bar.jpg') }}", "{{ asset('images/home-welcome-tutorial/narrow-tutorial-search-close.jpg') }}", "{{ asset('images/home-welcome-tutorial/narrow-tutorial-sign-in.jpg') }}"]'
        >
            <div class="home-welcome-popup-content">
                <h2>Welcome to Clean n Ideas!</h2>
                <div class="home-welcome-popup-description">
                    <p>To get familiar with the website, use the next and previous buttons to go through the tutorial! If you're already experienced, then feel free to click off.</p>
                </div>
                <img id="tutorial-img" alt="Welcome Image" class="home-welcome-popup-image">
                <div class="home-welcome-popup-navigation">
                    <button id="prev-tutorial-btn">
                        << Previous
                    </button>
                    <button id="next-tutorial-btn">
                        Next >>
                    </button>
                </div>
                <div class="home-welcome-popup-close-buttons">
                    <button id="close-welcome-btn">
                        Close first time tutorial
                    </button>
                </div>
            </div>
        </div>
    @endpush
</x-main-layout>