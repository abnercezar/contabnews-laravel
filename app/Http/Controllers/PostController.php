<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return response()->json(Post::all());
    }

    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->validated());
        return response()->json($post, 201);
    }
    public function create()
    {
        return \Inertia\Inertia::render('CreateContent');
    }

    public function show(Post $post)
    {
        return response()->json($post);
    }

    public function update(StorePostRequest $request, Post $post)
    {
        $post->update($request->validated());
        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(null, 204);
    }
}
