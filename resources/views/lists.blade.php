<x-main-layout title="Lists">
    <div class="two-to-one-grid">
        <section class="lists">
            <p>Currently you have no lists</p>
        </section>
        <section class="options">
            <h2>Lists</h2>
            <button id="lists-make-new-list-button" data-popup-target="lists-make-new-list-popup">Make a list</button>
        </section>
    </div>

    @push('popup')
        <div id="lists-make-new-list-popup">
            <div class="lists-popup-close-container">
                <button class="lists-popups-off-button">
                    <svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="var(--text-color)">
                        <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
                    </svg>
                </button>
            </div>
            <div>
                <h4>Make a new list</h4>
                <form id="new-list-form">
                    <input id="list-name-input" name="" placeholder="List name" />
                    <label>
                        Public
                        <input type="checkbox" />
                    </label>
                </form>
            </div>
        </div>
    @endpush
</x-main-layout>