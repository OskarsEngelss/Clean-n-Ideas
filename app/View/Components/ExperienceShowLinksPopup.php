<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Experience;

class ExperienceShowLinksPopup extends Component
{
    public $experience;

    public function __construct(Experience $experience)
    {
        $this->experience = $experience;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.experience-show-links-popup');
    }
}
