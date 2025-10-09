<form class="experience-show-favourite-form" id="experience-show-favourite-form" action="{{ route('tutorialList.favourite.store') }}" method="POST">
    @csrf
    <input type="hidden" name="experience_id" value="{{ $experience->id }}">
    <button class="experience-show-favourite-button" id="experience-show-favourite-button">
        <span id="svg-filled" style="{{ $favourited ? '' : 'display:none' }}">
            @include('experience.partials.favourite-filled-svg')
        </span>
        <span id="svg-empty" style="{{ $favourited ? 'display:none' : '' }}">
            @include('experience.partials.favourite-empty-svg')
        </span>
    </button>
</form>
