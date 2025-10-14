<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Collection;
use App\Models\Experience;
use App\Models\Comment;

class ExperienceShowCommentsPopup extends Component
{
    public $experience;
    public $comments;
    public $experienceSlug;

    public function __construct(Experience $experience, Collection  $comments, $experienceSlug)
    {
        $this->experience = $experience;
        $this->comments = $comments;
        $this->experienceSlug = $experienceSlug;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.experience-show-comments-popup');
    }
}
