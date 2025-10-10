<x-main-layout title="Search">
    <div class="two-to-one-grid">
        <section id="search-results" class="search-results">
            <x-search-results-tutorial-results :experiences="$experiences" />
            <x-search-results-user-results :users="$users" />
        </section>
        <section class="search-options">
            <h2>Search Results</h2>
            <div class="searched-for-container">
                <p>Searched for:</p>
                <p>{{ $query }}</p>
            </div>
        </section>
    </div>
</x-main-layout>