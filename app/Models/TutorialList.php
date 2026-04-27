<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TutorialListItem;
use App\Models\User;

class TutorialList extends Model
{
    // Atļautie lauki masveida ierakstīšanai
    protected $fillable = ['user_id', 'name', 'is_favourite', 'is_public'];

    public function user() {
        return $this->belongsTo(User::class); // Pieder vienam lietotājam
    }

    public function tutorialListItems() {
        return $this->hasMany(TutorialListItem::class, 'tutorial_list_id', 'id'); // Satur daudzus saraksta vienumus
    }

    public function experiences() {
        // Tieša piekļuve datiem caur starptabulu
        return $this->hasManyThrough(Experience::class, TutorialListItem::class, 'tutorial_list_id', 'id', 'id', 'tutorial_id');
    }

    public function getExperiencesAttribute() {
        // Dinamiska unikālu pieredžu atlase no kolekcijas
        return ($this->tutorialListItems ?? collect())
            ->pluck('experience')
            ->flatten()
            ->unique('id');
    }
}