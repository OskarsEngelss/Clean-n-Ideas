<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Experience;
use App\Models\User;

class SearchController extends Controller
{
    public function search(Request $request) {
        $query = $request->input('q');
        $offset = $request->input('offset', null); // default 0

        $stopWords = [
            'a', 'an', 'and', 'are', 'as', 'at', 'be', 'by', 'for',
            'from', 'how', 'in', 'is', 'it', 'of', 'on', 'or', 'that',
            'the', 'to', 'was', 'what', 'when', 'where', 'which', 'with',
            'about', 'into', 'over', 'after', 'before', 'while', 'during', 'also'
        ];
        $terms = array_filter(explode(' ', strtolower($query)));
        $importantTerms = array_filter($terms, fn($word) => !in_array($word, $stopWords) && strlen($word) > 1);

        // Experiences (public only)
        $experiences = Experience::where('visibility', 'Public')->get()->map(function ($exp) use ($terms, $importantTerms) {
            $score = 0;
            foreach ($importantTerms as $term) {
                if (stripos($exp->title, $term) !== false) $score += 5;
                if (stripos($exp->category, $term) !== false) $score += 3;
                if (stripos($exp->description, $term) !== false) $score += 2;
            }
            foreach ($terms as $term) {
                if (in_array($term, $importantTerms)) continue;
                if (stripos($exp->title, $term) !== false) $score += 1;
                if (stripos($exp->description, $term) !== false) $score += 1;
            }
            $exp->search_score = $score;
            return $exp;
        })
        ->filter(fn($exp) => $exp->search_score > 0)
        ->sortByDesc('search_score')
        ->values();

        // Users
        $users = User::all()->map(function ($user) use ($terms, $importantTerms) {
            $score = 0;
            foreach ($importantTerms as $term) {
                if (stripos($user->name, $term) !== false) $score += 5;
                if (stripos($user->description, $term) !== false) $score += 3;
            }
            foreach ($terms as $term) {
                if (in_array($term, $importantTerms)) continue;
                if (stripos($user->name, $term) !== false) $score += 1;
                if (stripos($user->description, $term) !== false) $score += 1;
            }
            $user->search_score = $score;
            return $user;
        })
        ->filter(fn($user) => $user->search_score > 0)
        ->sortByDesc('search_score')
        ->values();

        // Slice results based on offset
        if ($offset !== null) {
            $type = request('type');

            if ($type === 'experiences') {
                $limit = 6;
                $experiencesSlice = $experiences->slice($offset, $limit)->values();
                $experiencesSlice->load('user');

                $experiencesData = $experiencesSlice->map(function ($exp) {
                    return [
                        'slug' => $exp->slug,
                        'title' => $exp->title,
                        'thumbnail_url' => $exp->thumbnail_url,
                        'likes_count' => $exp->likes()->count(),
                        'dislikes_count' => $exp->dislikes()->count(),
                        'created_at_human' => $exp->created_at->diffForHumans(),
                        'user' => [
                            'name' => $exp->user->name ?? 'Unknown',
                            'profile_picture' => $exp->user->profile_picture ?? null,
                        ],
                    ];
                });

                return response()->json([
                    'experiences' => [
                        'items' => $experiencesData,
                        'hasmore' => $experiences->count() > $offset + $limit,
                    ],
                ]);
            }

            if ($type === 'users') {
                $limit = 4;
                $usersSlice = $users->slice($offset, $limit)->values();

                return response()->json([
                    'users' => [
                        'items' => $usersSlice,
                        'hasmore' => $users->count() > $offset + $limit,
                    ],
                ]);
            }

            // fallback if type missing
            return response()->json(['error' => 'Invalid or missing type'], 400);
        }

        $experiencesData = (object) [
            'items' => $experiences->take(6),
            'hasmore' => $experiences->count() > 6
        ];
        $usersData = (object) [
            'items' => $users->take(4),
            'hasmore' => $users->count() > 4
        ];

        return view('search.results', [
            'query' => $query,
            'experiences' => $experiencesData,
            'users' => $usersData,
        ]);
    }
}
