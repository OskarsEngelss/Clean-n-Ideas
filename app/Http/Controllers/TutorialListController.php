<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TutorialList;
use App\Models\TutorialListItem;
use App\Models\User;
use App\Models\Experience;
use Illuminate\Support\Facades\Auth;

class TutorialListController extends Controller
{
    public function index($id) {
        if (auth()->id() !== (int)$id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        
        $user = User::find($id);

        $lists = $user->tutorialLists()
                ->withCount('tutorialListItems')
                ->latest()
                ->take(8)
                ->get();

        return view('list.index', compact('lists'));
    }

    public function show($id, $list_id) {
        $user = User::find($id);
        $list = TutorialList::find($list_id);

        if (!$list->is_public && auth()->id() !== $list->user_id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

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
            'experience_id' => ['nullable', 'exists:experiences,id'],
        ]);

        $list = TutorialList::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'is_favourite' => false,
            'is_public' => $validated['is_public'],
        ]);

        $list->loadCount('tutorialListItems');

        if(!empty($validated['experience_id'])) {
            $experienceId = $validated['experience_id'];

            TutorialListItem::create([
                'tutorial_list_id' => $list->id,
                'tutorial_id' => $validated['experience_id'],
            ]);

            $saved = true;
            return view('partials._list-option', compact('list', 'experienceId', 'saved'));
        }
        return view('partials._list', compact('list'));
    }

    public function storeTutorial(Request $request) {
        $validated = $request->validate([
            'tutorial_list_id' => ['required', 'exists:tutorial_lists,id'],
            'tutorial_id' => ['required', 'exists:experiences,id'],
        ]);

        $list = auth()->user()->tutorialLists()->find($validated['tutorial_list_id']);
        if (!$list) {
            return response()->json(['success' => false, 'message' => 'Invalid list'], 403);
        }

        $existing = $list->tutorialListItems()
            ->where('tutorial_id', $validated['tutorial_id'])
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['success' => true, 'action' => 'removed']);
        } else {
            TutorialListItem::create([
                'tutorial_list_id' => $validated['tutorial_list_id'],
                'tutorial_id' => $validated['tutorial_id'],
            ]);
            return response()->json(['success' => true, 'action' => 'added']);
        }
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

    public function listsLoadMore(Request $request) {
        $page = (int) $request->get('page', 1);
        $perPage = 8;
        $skip = ($page - 1) * $perPage;

        $lists = auth()->user()->tutorialLists()
                ->withCount('tutorialListItems')
                ->latest()
                ->skip($skip)
                ->take($perPage)
                ->get();

        if ($lists->isEmpty()) {
            return response('', 200);
        }

        return view('partials._list-multiple', compact('lists'));
    }

    public function experiencesListsLoadMore(Request $request, $slug) {
        $page = (int) $request->get('page', 1);

        $experience = Experience::where('slug', $slug)->firstOrFail();
        $experienceId = $experience->id;

        $perPage = 8;
        $skip = ($page - 1) * $perPage;

        $lists = auth()->user()->tutorialLists()
                ->where('is_favourite', false)
                ->with('tutorialListItems:tutorial_list_id,tutorial_id')
                ->latest()
                ->skip($skip)
                ->take($perPage)
                ->get();

        if ($lists->isEmpty()) {
            return response('', 200);
        }

        return view('partials._list-option-multiple', compact('lists', 'experienceId'));
    }

    public function destroy($id) {
        $list = TutorialList::findOrFail($id);

        if ($list->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $list->delete();

        return redirect()
            ->route('list.index', ['id' => auth()->id()])
            ->with('success', 'List deleted successfully.');
    }
}
