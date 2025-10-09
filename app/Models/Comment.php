<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'tutorial_id',
        'user_id',
        'parent_id',
        'content',
    ];

    public function experience() {
        return $this->belongsTo(Experience::class, 'tutorial_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function parent() {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->withCount([
            'likes as likes_count',
            'dislikes as dislikes_count',
        ])->with('user');
    }

    public function likes() {
        return $this->hasMany(CommentLike::class)->where('type', 'like');
    }

    public function dislikes() {
        return $this->hasMany(CommentLike::class)->where('type', 'dislike');
    }

    public function userReaction() {
        return $this->hasOne(CommentLike::class, 'comment_id')
                    ->where('user_id', auth()->id());
    }
}
