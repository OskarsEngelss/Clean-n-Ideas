<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TutorialCard extends Component
{
    public $experience;
    public $thumbnail;
    public $user;
    public $savedCount;
    public $url;

    public function __construct($experience, $thumbnail, $user, $savedCount, $url = '#')
    {
        $this->experience = $experience;
        $this->thumbnail = $thumbnail;
        $this->user = $user;
        $this->savedCount = $savedCount;
        $this->url = $url;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tutorial-card');
    }
}
