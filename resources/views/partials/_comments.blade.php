@foreach($comments as $comment)
    <x-comment-component
        :comment="$comment"
    ></x-comment-component>
@endforeach