<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TutorialList;
use App\Models\Experience;

class TutorialListItem extends Model
{
    protected $fillable = ['tutorial_list_id', 'tutorial_id'];

    public function tutorialList() {
        return $this->belongsTo(TutorialList::class);
    }

    public function experience()
    {
        return $this->belongsTo(Experience::class, 'tutorial_id');
    }
}