<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        return response()->json(Post::all());
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $data['author'] = $user ? $user->name : 'Anônimo';
        $post = Post::create($data);
        return response()->json($post, 201);
    }
    public function create()
    {
        return Inertia::render('CreateContent');
    }

    public function show(Post $post)
    {
        return response()->json($post);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        if ($post->author !== $request->user()->name) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }
        $validated = app(UpdatePostRequest::class)->validated();
        $post->update($validated);
        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $user = auth()->user();
        if (!$user || $post->author !== $user->name) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }
        $post->delete();
        return response()->json(null, 204);
    }
}
