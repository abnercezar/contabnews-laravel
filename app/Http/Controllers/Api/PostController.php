<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:120',
            'content' => 'required|string|max:20000',
            'source_url' => 'nullable|url',
            'isSponsoredContent' => 'boolean',
        ]);

        $user = $request->user();
        $validated['author'] = $user ? $user->name : 'Anônimo';

        $post = Post::create($validated);

        return response()->json($post, 201);
    }
}
