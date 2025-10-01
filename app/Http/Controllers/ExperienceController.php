<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Experience;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\TutorialList;
use App\Models\TutorialListItem;
use App\Models\Comment;

class ExperienceController extends Controller
{
    public function index() {
        $experiences = Experience::with('user')->withCount('tutorialListItems')->get();
        return view('home', compact('experiences'));
    }

    public function yourExperiences(Request $request) {
        $order = $request->query('order', 'desc');
        $experiences = auth()->user()->experiences()->orderBy('created_at', $order)->get();

        return view('your-experiences', compact('experiences', 'order'));
    }

    public function create() {
        return view('experience.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'category' => 'required|string',
            'title' => 'required|string|max:70',
            'description' => 'required|string|max:400',
            'tutorial' => 'required|string|max:15000',
            'visibility' => 'required|in:Public,Unlisted,Private',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        auth()->user()->experiences()->create($validated);

        return redirect()->route('your-experiences')->with('success', 'Your experience has been recorded!');
    }

    public function show(Experience $experience) {
        $favourited = false;
        $creator = $experience->user;
        $followersCount = $creator->followers()->count();

        $comments = Comment::where('tutorial_id', $experience->id)
                   ->whereNull('parent_id')
                   ->with('replies.user')
                   ->latest()
                   ->get();
        
        if (Auth::check()) {
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
            if ($favouritesList) {
                $favourited = $favouritesList->tutorialListItems
                    ->pluck('experience.id')
                    ->contains($experience->id);
            }
        }

        return view('experience.show', compact('experience', 'followersCount', 'favourited', 'comments'));
    }

    public function edit($id) {
        // Return a view to edit the resource
    }

    public function update(Request $request, $id) {
        // Handle update logic
    }

    public function destroy($id) {
        // Delete the resource
    }
}
