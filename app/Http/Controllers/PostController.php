<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Actions\CreatePostAction;
use App\Actions\UpdatePostAction;
use App\Actions\DeletePostAction;

class PostController extends Controller
{
    public function index()
    {
        // Pagination + simple caching to avoid returning entire dataset and reduce DB load.
        $perPage = (int) request()->get('per_page', 50);
        $page = (int) request()->get('page', 1);
        $cacheKey = "posts:page:{$page}:per:{$perPage}";

        $data = Cache::remember($cacheKey, 30, function () use ($perPage) {
            // Order by newest first. Adjust eager loading here if you add relations (->with('authorRelation')).
            return Post::orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->items();
        });

        return response()->json($data);
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        // Use CreatePostAction to keep controller thin and delegate business logic.
        $action = app(CreatePostAction::class);
        $post = $action->execute($data, $user);

        return response()->json($post, 201);
    }
    public function create()
    {
        return Inertia::render('CreateContent');
    }

    public function publicationsPage()
    {
        $newPostRaw = request('newPost');
        $newPost = null;
        if ($newPostRaw) {
            $decoded = json_decode($newPostRaw, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $newPost = $decoded;
            }
        }
        return Inertia::render('Publications', [
            'newPost' => $newPost,
        ]);
    }

    public function show(Post $post)
    {
        return response()->json($post);
    }

    /**
     * Render the single-post web page (Inertia) at /content/{post}
     */
    public function showPage(Post $post)
    {
        // 'user' relation does not exist on Post; posts store author as a scalar 'author' field.
        // Eager-load comment/reaction users but not a non-existent post->user relation.
        return Inertia::render('Content', [
            'post' => $post->load('comments.user', 'reactions.user'),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $user = $request->user();
        if ($post->author !== ($user?->name ?? null)) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $validated = $request->validated();

        $action = app(UpdatePostAction::class);
        $post = $action->execute($post, $validated);

        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $user = auth()->user();
        if (!$user || $post->author !== $user->name) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $action = app(DeletePostAction::class);
        $action->execute($post);

        return response()->json(null, 204);
    }
}
