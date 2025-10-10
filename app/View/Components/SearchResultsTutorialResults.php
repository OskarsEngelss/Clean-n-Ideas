<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Experience;

class SearchResultsTutorialResults extends Component
{
    public $experiences;

    public function __construct($experiences)
    {
        $this->experiences = $experiences;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.search-results-tutorial-results');
    }
}
