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

        $favouritesList->load('tutorialListItems.experiences');
        $experiences = $favouritesList ? $favouritesList->experiences : collect();

        return view('favourites', compact('experiences'));
    }

    public function favouriteStore(Request $request) {
        //Atrod autentificēto lietotāju
        $user = Auth::user();

        //Izveido jaunu ierakstu TutorialListItem tabulā
        TutorialListItem::create([
            //Izmantojot Eloquent Relationships, kuru deklarēja TutorialListItem modelī, atrod
            //pareizo mīļāko sarakstu un to ievieto tutorial_list_id šūnā
            'tutorial_list_id' => $user->tutorialLists()->where('is_favourite', true)->value('id'),
            //Izmantojot Laravel Request atrod pamācības id, kuru vēlas saglabāt
            'tutorial_id' => $request->experience_id,
        ]);

        //Atgriežas atpakaļ uz pamācību pēc tam, kad tā ir saglabāta
        return back();
    }
}
