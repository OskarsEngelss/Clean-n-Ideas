<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TutorialList;
use App\Models\TutorialListItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TutorialListController extends Controller
{
    public function index($id) {
        $user = User::find($id);

        $lists = $user->tutorialLists()
                ->latest()
                ->take(8)
                ->get();

        return view('list.index', compact('lists'));
    }

    public function show($id, $list_id) {
        $user = User::find($id);
        $list = TutorialList::find($list_id);
        $experiences = $list->experiences()
                        ->latest()
                        ->take(8)
                        ->get();

        return view('list.show', compact('list', 'experiences'));
    }

    public function storeList(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_public' => ['required', 'boolean'],
        ]);

        TutorialList::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'is_favourite' => false,
            'is_public' => $validated['is_public'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Playlist created successfully!',
            'list' => $list,
        ]);
    }

    // public function index(Request $request) {
    //     $user = Auth::user();

    //     $favouritesList = $user->tutorialLists()->where('is_favourite', true)->first();

    //     if (!$favouritesList) {
    //         $favouritesList = TutorialList::create([
    //             'user_id' => $user->id,
    //             'name' => 'Favourites',
    //             'is_favourite' => true,
    //             'is_public' => false,
    //         ]);
    //     }

    //     $favouritesList->load('tutorialListItems.experience');
    //     $experiences = $favouritesList ? $favouritesList->experiences : collect();

    //     return view('favourites', compact('experiences'));
    // }

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
