<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Experience;

class CommentController extends Controller
{
    /**
     * Saglabā jaunu komentāru vai atbildi datubāzē.
     * * Šī funkcija validē ienākošos datus, piesaista autorizēto lietotāju
     * un atgriež noformētu HTML komponenti (component) JSON formātā priekš AJAX pieprasījumiem.
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'tutorial_id' => 'required|exists:experiences,id',
            'content' => 'required|string|max:300',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'tutorial_id' => $validated['tutorial_id'],
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        $comment->load('user', 'replies')->loadCount(['likes as likes_count', 'dislikes as dislikes_count']);

        if ($comment->parent_id) {
            $view = view('components.reply-component', ['reply' => $comment])->render();
        } else {
            $view = view('components.comment-component', ['comment' => $comment])->render();
        }

        return response()->json([
            'html' => $view,
            'parent_id' => $comment->parent_id
        ]);
    }


    /**
     * Pievieno, noņem vai maina reakciju (like/dislike) komentāram.
     * * Šī funkcija pārbauda vai lietotājs jau ir reaģējis uz komentāru un veic attiecīgo darbību:
     * - Ja reakcijas nav, tā tiek izveidota (added).
     * - Ja lietotājs spiež uz tās pašas reakcijas, tā tiek dzēsta (removed).
     * - Ja lietotājs maina izvēli, tips tiek atjaunināts (updated).
     * Atgriež jauno reakciju skaitu JSON formātā.
     */
    public function toggleReaction(Request $request) {
        $validated = $request->validate([
            'comment_id' => 'required|integer|exists:comments,id',
            'type' => 'required|in:like,dislike',
        ]);

        $userId = auth()->id();
        $commentId = $validated['comment_id'];
        $type = $validated['type'];

        $existing = CommentLike::where('comment_id', $commentId)
            ->where('user_id', $userId)
            ->first();

        if (!$existing) {
            CommentLike::create([
                'comment_id' => $commentId,
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

        $likes = CommentLike::where('comment_id', $commentId)->where('type', 'like')->count();
        $dislikes = CommentLike::where('comment_id', $commentId)->where('type', 'dislike')->count();

        return response()->json([
            'status' => $status,
            'likes' => $likes,
            'dislikes' => $dislikes,
        ]);
    }


    /**
     * Ielādē papildus komentārus (comments) konkrētajā pamācībā (experience).
     * * Funkcija izmanto pagināciju (pagination), lai izlaistu jau redzamos komentārus,
     * un tad paņem nākamos astoņus. Tiek ielādētas arī atbildes (replies), 
     * lietotāju dati (user) un reakciju skaits (likes/dislikes count).
     * Atgriež tikai HTML skatu (view) ar komentāriem.
     */
    public function experiencesCommentsLoadMore(Request $request, $slug) {
        $page = (int) $request->get('page', 1);
        $perPage = 8;
        $skip = ($page - 1) * $perPage;

        $experience = Experience::where('slug', $slug)->firstOrFail();

        $comments = Comment::where('tutorial_id', $experience->id)
                    ->whereNull('parent_id')
                    ->with('replies.user')
                    ->with('userReaction')
                    ->withCount([
                            'likes as likes_count',
                            'dislikes as dislikes_count',
                        ])
                    ->latest()
                    ->skip($skip)
                    ->take($perPage)
                    ->get();

        if ($comments->isEmpty()) {
            return response('', 200);
        }

        return view('partials._comments', compact('comments'));
    }
}
