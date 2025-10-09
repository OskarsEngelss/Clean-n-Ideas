<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TutorialList;
use App\Models\TutorialListItem;
use Illuminate\Support\Facades\Auth;

class TutorialListController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();

        $favouritesList = $user->tutorialLists()->where('is_favourite', true)->first();

        if (!$favouritesList) {
            $favouritesList = TutorialList::create([
                'user_id' => $user->id,
                'name' => 'Favourites',
                'is_favourite' => true,
                'is_public' => false,
            ]);
        }

        $favouritesList->load('tutorialListItems.experience');
        $experiences = $favouritesList ? $favouritesList->experiences : collect();

        return view('favourites', compact('experiences'));
    }

    public function favouriteStore(Request $request) {
        $user = Auth::user();

        $favouritesList = $user->tutorialLists()->firstOrCreate(
            ['is_favourite' => true],
            ['name' => 'Favourites', 'is_public' => false]
        );

        $existing = TutorialListItem::where('tutorial_list_id', $favouritesList->id)
            ->where('tutorial_id', $request->experience_id)
            ->first();

        $favourited = false;

        if ($existing) {
            $existing->delete();
        } else {
            TutorialListItem::create([
                'tutorial_list_id' => $favouritesList->id,
                'tutorial_id' => $request->experience_id,
            ]);
            $favourited = true;
        }

        return response()->json([
            'success' => true,
            'favourited' => $favourited,
        ]);
    }
}
