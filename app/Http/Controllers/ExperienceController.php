<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Experience;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\TutorialList;
use App\Models\TutorialListItem;
use App\Models\Comment;
use App\Models\TutorialMedia;
use App\Models\TutorialLike;
use App\Models\TutorialOutsideLink;


class ExperienceController extends Controller
{
    public function index() {
        $experiences = Experience::with('user')->where('visibility', 'Public')->latest()->take(18)->get();
        return view('home', compact('experiences'));
    }
    
    public function loadMore(Request $request) {
        $page = (int) $request->get('page', 1);
        $perPage = 18;
        $skip = ($page - 1) * $perPage;

        $experiences = Experience::with('user')
            ->where('visibility', 'Public')
            ->latest()
            ->skip($skip)
            ->take($perPage)
            ->get();

        if ($experiences->isEmpty()) {
            return response('', 200);
        }

        return view('partials._experience', compact('experiences'));
    }


    public function yourExperiences() {
        $experiences = auth()->user()->experiences()
                        ->latest()
                        ->take(8)
                        ->get();

        return view('your-experiences', compact('experiences'));
    }
    public function yourExperiencesLoadMore(Request $request) {
        $page = (int) $request->get('page', 1);
        $perPage = 8;
        $skip = ($page - 1) * $perPage;

        $experiences = auth()->user()->experiences()
            ->latest()
            ->skip($skip)
            ->take($perPage)
            ->get();

        if ($experiences->isEmpty()) {
            return response('', 200);
        }

        return view('partials._experience', compact('experiences'));
    }

    public function create() {
        return view('experience.create');
    }

    public function uploadTemp(Request $request) {
        $request->validate(['file' => 'required|file|mimes:jpg,png,gif,mp4']);
        $file = $request->file('file');
        $path = $file->store('temp', 'public');
        return response()->json(['tempPath' => "/storage/$path"]);
    }
    public function deleteTemp(Request $request) {
        $path = $request->input('path');

        // Remove "/storage/" prefix to match disk path
        $diskPath = str_replace('/storage/', '', $path);
        if (Storage::disk('public')->exists($diskPath)) {
            Storage::disk('public')->delete($diskPath);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:70',
            'category' => 'required|string',
            'description' => 'required|string|max:400',
            'tutorial' => 'required|string|max:15000',
            'visibility' => 'required|in:Public,Unlisted,Private',

            //Url validation
            'urls' => 'nullable|array',
            'urls.*' => 'required|url',
        ]);

        if (empty(trim(strip_tags(html_entity_decode($request->input('tutorial')))))) {
            return response()->json([
                'errors' => [
                    'tutorial' => ['The tutorial field cannot be empty.']
                ]
            ], 422);
        }

        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['thumbnail'] = 'images/defaults/' . mb_strtolower($validated['category'], 'UTF-8') . '-default-thumbnail.webp';
        $tutorial = auth()->user()->experiences()->create($validated);

        $tutorialHtml = $request->input('tutorial');

        libxml_use_internal_errors(true); // suppress warnings from malformed HTML
        $doc = new \DOMDocument();
        $doc->loadHTML(mb_convert_encoding($tutorialHtml, 'HTML-ENTITIES', 'UTF-8'));

        $spans = $doc->getElementsByTagName('span');

        $processedPaths = [];

        foreach ($spans as $span) {
            if ($span->getAttribute('class') === 'tutorial-textarea-media-wrapper') {
                // Find the <img> or <video> inside
                $media = null;
                foreach ($span->getElementsByTagName('*') as $child) {
                    if (in_array($child->tagName, ['img', 'video'])) {
                        $media = $child;
                        break;
                    }
                }

                if ($media) {
                    $tempPath = $media->getAttribute('data-temp-path');
                    if ($tempPath && str_starts_with($tempPath, '/storage/temp/')) {
                        $diskPath = str_replace('/storage/', '', $tempPath);

                        // Check if this path was already processed
                        if (isset($processedPaths[$diskPath])) {
                            $newPath = $processedPaths[$diskPath];
                        } elseif (Storage::disk('public')->exists($diskPath)) {
                            $newPath = 'uploads/' . basename($diskPath);
                            Storage::disk('public')->move($diskPath, $newPath);

                            $processedPaths[$diskPath] = $newPath;

                            // Save media record
                            TutorialMedia::create([
                                'tutorial_id' => $tutorial->id,
                                'user_id' => auth()->id(),
                                'type' => $media->tagName === 'img' ? 'image' : 'video',
                                'path' => $newPath,
                            ]);
                        } else {
                            $newPath = null; // file doesn't exist anymore
                        }

                        if ($newPath) {
                            $media->setAttribute('src', '/storage/' . $newPath);
                        }
                    }

                    // Clean the span: remove all children except <img> or <video>
                    while ($span->firstChild) {
                        $span->removeChild($span->firstChild);
                    }
                    $span->appendChild($media);
                }
            }
        }

        // Save cleaned HTML
        $body = $doc->getElementsByTagName('body')->item(0);
        $tutorial->tutorial = '';
        foreach ($body->childNodes as $child) {
            $tutorial->tutorial .= $doc->saveHTML($child);
        }

        $tutorial->save();

        if (!empty($request->input('urls'))) {
            foreach ($request->input('urls') as $url) {
                TutorialOutsideLink::create([
                    'tutorial_id' => $tutorial->id,
                    'url' => $url,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Your experience has been recorded!',
            'tutorial_id' => $tutorial->id,
            'redirect' => route('your-experiences'),
            'requestData' => $request->all(),
        ]);
    }



    public function show(Experience $experience) {
        if ($experience->visibility === 'Private' && $experience->user_id !== auth()->id()) {
            return back();
        }

        $experience->load('media');
        $experience->load('userReaction');
        $experience->load('links');

        $favourited = false;
        $creator = $experience->user;
        $followersCount = $creator->followers()->count();

        $comments = Comment::where('tutorial_id', $experience->id)
                   ->whereNull('parent_id')
                   ->with('replies.user')
                   ->with('userReaction')
                   ->withCount([
                        'likes as likes_count',
                        'dislikes as dislikes_count',
                    ])
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

    public function destroy(Request $request, $id) {
        $experience = Experience::with('media')->findOrFail($id);

        $request->validate([
            'title_confirmation' => ['required', 'string', function($attribute, $value, $fail) use ($experience) {
                if ($value !== $experience->title) {
                    $fail('The title does not match. Deletion aborted.');
                }
            }],
        ]);

        foreach ($experience->media as $file) {
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
        }

        $experience->delete();

        return redirect()->route('home')->with('success', 'Experience deleted successfully.');
    }

    public function toggleReaction(Request $request) {
        if (!auth()->check()) {
            return response()->json(['message' => 'You must be logged in'], 401);
        }

        $validated = $request->validate([
            'tutorial_id' => 'required|integer|exists:experiences,id',
            'type' => 'required|in:like,dislike',
        ]);

        $userId = auth()->id();
        $tutorialId = $validated['tutorial_id'];
        $type = $validated['type'];

        $existing = TutorialLike::where('tutorial_id', $tutorialId)
            ->where('user_id', $userId)
            ->first();

        if (!$existing) {
            TutorialLike::create([
                'tutorial_id' => $tutorialId,
                'user_id' => $userId,
                'type' => $type,
            ]);
            $status = 'added';
        } elseif ($existing->type === $type) {
            $existing->delete();
            $status = 'removed';
        } else {
            $existing->type = $type;
            $existing->save();
            $status = 'updated';
        }

        $likes = TutorialLike::where('tutorial_id', $tutorialId)->where('type', 'like')->count();
        $dislikes = TutorialLike::where('tutorial_id', $tutorialId)->where('type', 'dislike')->count();

        return response()->json([
            'status' => $status,
            'likes' => $likes,
            'dislikes' => $dislikes,
            'tutorial_id' => $tutorialId,
        ]);
    }
}
