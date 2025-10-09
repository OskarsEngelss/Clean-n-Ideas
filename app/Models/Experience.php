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

    public function media()
    {
        return $this->hasMany(TutorialMedia::class, 'tutorial_id');
    }

    public function likes() {
        return $this->hasMany(TutorialLike::class, 'tutorial_id')->where('type', 'like');
    }

    public function dislikes() {
        return $this->hasMany(TutorialLike::class, 'tutorial_id')->where('type', 'dislike');
    }

    public function userReaction() {
        return $this->hasOne(TutorialLike::class, 'tutorial_id')
            ->where('user_id', auth()->id());
    }
}
