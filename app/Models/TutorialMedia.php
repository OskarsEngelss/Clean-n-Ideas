<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorialMedia extends Model
{
    protected $fillable = ['tutorial_id', 'user_id', 'type', 'path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
