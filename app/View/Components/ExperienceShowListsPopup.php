<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Collection;
use App\Models\TutorialList;

class ExperienceShowListsPopup extends Component
{
    public $lists;
    public $experienceId;
    public $experienceSlug;

    public function __construct(Collection $lists, $experienceId, $experienceSlug)
    {
        $this->lists = $lists;
        $this->experienceId = $experienceId;
        $this->experienceSlug = $experienceSlug;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.experience-show-lists-popup');
    }
}
