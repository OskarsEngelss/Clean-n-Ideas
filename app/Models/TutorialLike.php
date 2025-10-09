<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorialLike extends Model
{
    protected $fillable = [
        'tutorial_id',
        'user_id',
        'type',
    ];

    public function tutorial() {
        return $this->belongsTo(Experience::class, 'tutorial_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
