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

    public function replies() {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
