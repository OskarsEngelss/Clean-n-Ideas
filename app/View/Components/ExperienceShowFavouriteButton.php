<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ExperienceShowFavouriteButton extends Component
{
    public $experienceId;
    public $favourited;
    public $favouritesListId;
    

    public function __construct($experienceId, $favourited, $favouritesListId)
    {
        $this->experienceId = $experienceId;
        $this->favourited = $favourited;
        $this->favouritesListId = $favouritesListId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.experience-show-favourite-button');
    }
}
