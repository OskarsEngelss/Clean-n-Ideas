<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['category', 'title', 'description', 'tutorial', 'user_id', 'slug', 'visibility'];

    public function tutorialListItems()
    {
        return $this->hasMany(TutorialListItem::class, 'tutorial_id'); 
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
