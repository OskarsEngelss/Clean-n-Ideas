<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = ['category', 'title', 'description', 'tutorial', 'user_id', 'slug', 'visibility', 'thumbnail'];

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

    public function links() {
        return $this->hasMany(TutorialOutsideLink::class, 'tutorial_id');
    }
}
