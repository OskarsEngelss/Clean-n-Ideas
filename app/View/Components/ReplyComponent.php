<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Comment;

class ReplyComponent extends Component
{
     public $reply;

    public function __construct(Comment $reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.reply-component');
    }
}
