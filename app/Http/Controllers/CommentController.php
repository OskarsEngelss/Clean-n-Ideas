<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\CommentLike;

class CommentController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'tutorial_id' => 'required|exists:experiences,id',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'tutorial_id' => $validated['tutorial_id'],
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        $comment->load('user', 'replies');

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

    public function toggleReaction(Request $request) {
        $validated = $request->validate([
            'comment_id' => 'required|integer|exists:comments,id',
            'type' => 'required|in:like,dislike',
        ]);

        $userId = auth()->id(); // Assumes you're using Laravel Auth
        $commentId = $validated['comment_id'];
        $type = $validated['type'];

        $existing = CommentLike::where('comment_id', $commentId)
            ->where('user_id', $userId)
            ->first();

        if (!$existing) {
            // No prior reaction → create new
            CommentLike::create([
                'comment_id' => $commentId,
                'user_id' => $userId,
                'type' => $type,
            ]);
            $status = 'added';
        } elseif ($existing->type === $type) {
            // Same reaction clicked again → remove
            $existing->delete();
            $status = 'removed';
        } else {
            // Opposite reaction → update
            $existing->type = $type;
            $existing->save();
            $status = 'updated';
        }

        // Optional: Return counts for frontend
        $likes = CommentLike::where('comment_id', $commentId)->where('type', 'like')->count();
        $dislikes = CommentLike::where('comment_id', $commentId)->where('type', 'dislike')->count();

        return response()->json([
            'status' => $status,
            'likes' => $likes,
            'dislikes' => $dislikes,
        ]);
    }
}
