<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();
        $validated['author'] = $user ? $user->name : 'Anônimo';
        $post = Post::create($validated);
        return response()->json($post, 201);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        if ($post->author !== $request->user()->name) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }
        $validated = $request->validated();
        $post->update($validated);
        return response()->json($post);
    }

    public function destroy(Request $request, Post $post)
    {
        if ($post->author !== $request->user()->name) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }
        $post->delete();
        return response()->json(null, 204);
    }
}
