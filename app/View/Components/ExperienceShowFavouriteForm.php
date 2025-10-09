<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Experience;

class ExperienceShowFavouriteForm extends Component
{
    public $experience;
    public $favourited;

    public function __construct(Experience $experience, $favourited)
    {
        $this->experience = $experience;
        $this->favourited = $favourited;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.experience-show-favourite-form');
    }
}
