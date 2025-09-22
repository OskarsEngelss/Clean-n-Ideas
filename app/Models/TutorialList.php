<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TutorialListItem;
use App\Models\User;

class TutorialList extends Model
{
    protected $fillable = ['user_id', 'name', 'is_favourite', 'is_public'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tutorialListItems() {
        return $this->hasMany(TutorialListItem::class);
    }

    public function getExperiencesAttribute() {
        return ($this->tutorialListItems ?? collect())
            ->pluck('experiences')
            ->flatten()
            ->unique('id');
    }
}