<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(StoreCommentRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        $comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => $data['post_id'],
            'body' => $data['body'],
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        // Load user relation to return name in response
        $comment->load('user');

        return response()->json($comment, 201);
    }
}
