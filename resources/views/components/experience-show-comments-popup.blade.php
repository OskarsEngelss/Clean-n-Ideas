@push('popup')
    <div id="experience-show-comments-popup" class="default-popup-style">
        <x-popup-close-component />
        <div class="experience-show-popup-comments-container">
            @forelse($comments as $comment)
                <x-comment-component
                    :comment="$comment"
                ></x-comment-component>
            @empty
                <p id="no-comments-message">No comments available</p>
            @endforelse
        </div>
        @if(Auth::user())
            <form action="{{ route('comment.store') }}" id="experience-show-comments-popup-form" method="POST">
                @csrf
                <input type="hidden" name="tutorial_id" value="{{ $experience->id }}">
                <input type="hidden" name="parent_id" id="parent_id" value="">

                <div id="experience-show-comments-popup-button-container">
                    <button type="button" id="experience-show-comments-popup-cancel-button">Cancel</button>
                    <button type="button" id="experience-show-comments-popup-emoji-button">😊</button>
                    <button id="experience-show-comments-popup-submit-button">Comment</button>
                </div>
                <textarea name="content" id="experience-show-comments-popup-input" rows="1" placeholder="Comment..."></textarea>
                <emoji-picker id="experience-show-comments-popup-emoji-picker" style="display:none;"></emoji-picker> <!-- The list of pickable emojis -->
            </form>
        @else
            <a class="experience-show-comments-popup-form-denied" href="/login">
                <div>
                    <p><span>Sign in</span> to comment/reply</p>
                </div>
            </a>
        @endif
    </div>
@endpush