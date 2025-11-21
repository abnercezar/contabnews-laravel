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
use App\Models\Reaction;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    public function index()
    {
        // Paginação + cache simples para evitar retornar todo o conjunto e reduzir a carga no banco.
        $perPage = (int) request()->get('per_page', 50);
        $page = (int) request()->get('page', 1);

        // Option to request only the current user's posts: ?mine=1
        $mine = filter_var(request()->get('mine', false), FILTER_VALIDATE_BOOLEAN);
        $user = request()->user();

        $cacheKey = "posts:page:{$page}:per:{$perPage}:mine:" . ($mine ? '1' : '0') . ($user ? ":user:{$user->id}" : ':guest');

        $data = Cache::remember($cacheKey, 30, function () use ($perPage, $mine, $user) {
            // Ordenar pelos mais recentes primeiro e eager-load de comentários + usuário do comentário.
            $query = Post::with(['comments.user'])->orderBy('created_at', 'desc');

            // Se o cliente pediu apenas os seus posts, aplique filtro por user_id
            if ($mine) {
                if ($user) {
                    // Admin vê tudo
                    if (($user->role ?? '') !== 'admin') {
                        $query->where('user_id', $user->id);
                    }
                } else {
                    // não autenticado: retorna coleção vazia
                    return [];
                }
            }

            return $query->paginate($perPage)->items();
        });

        // Se o usuário estiver autenticado, anexa `my_reaction` a cada post usando Redis (rápido) com fallback para BD.
        $user = request()->user();
        if ($user) {
            foreach ($data as $i => $post) {
                $postId = $post->id ?? ($post['id'] ?? null);
                $myReaction = null;
                if ($postId) {
                    // Verifica primeiro no Redis pela reação mais recente do usuário
                    $redisKey = "post:{$postId}:user:{$user->id}";
                    try {
                        $r = Redis::get($redisKey);
                        if ($r !== null) {
                            $myReaction = $r;
                        }
                    } catch (\Exception $e) {
                        // ignora erro do Redis e faz fallback para o BD
                    }

                    if ($myReaction === null) {
                        $myReaction = Reaction::where('post_id', $postId)
                            ->where('user_id', $user->id)
                            ->value('type');
                    }
                }

                // Normaliza para null ou 'up'|'down'
                $myReaction = $myReaction ?: null;

                // Garante que retornamos arrays ao cliente
                $arr = (is_array($post) ? $post : $post->toArray());
                $arr['my_reaction'] = $myReaction;
                $data[$i] = $arr;
            }
        } else {
            // converte modelos para arrays para manter formato consistente no cliente
            foreach ($data as $i => $post) {
                $data[$i] = (is_array($post) ? $post : $post->toArray());
                $data[$i]['my_reaction'] = null;
            }
        }

        // Se o usuário estiver autenticado, anexa `my_reaction` a cada post usando Redis (rápido) com fallback para BD.
        $user = request()->user();
        if ($user) {
            foreach ($data as $i => $post) {
                $postId = $post->id ?? ($post['id'] ?? null);
                $myReaction = null;
                if ($postId) {
                    // Verifica primeiro no Redis pela reação mais recente do usuário
                    $redisKey = "post:{$postId}:user:{$user->id}";
                    try {
                        $r = Redis::get($redisKey);
                        if ($r !== null) {
                            $myReaction = $r;
                        }
                    } catch (\Exception $e) {
                        // ignora erro do Redis e faz fallback para o BD
                    }

                    if ($myReaction === null) {
                        $myReaction = Reaction::where('post_id', $postId)
                            ->where('user_id', $user->id)
                            ->value('type');
                    }
                }

                // Normaliza para null ou 'up'|'down'
                $myReaction = $myReaction ?: null;

                // Garante que retornamos arrays ao cliente
                $arr = (is_array($post) ? $post : $post->toArray());
                $arr['my_reaction'] = $myReaction;
                $data[$i] = $arr;
            }
        } else {
            // converte modelos para arrays para manter formato consistente no cliente
            foreach ($data as $i => $post) {
                $data[$i] = (is_array($post) ? $post : $post->toArray());
                $data[$i]['my_reaction'] = null;
            }
        }

        return response()->json($data);
    }

    public function store(StorePostRequest $request)
    {
        // Autorização: apenas usuários autenticados podem criar publicações.
        $this->authorize('create', Post::class);

        $data = $request->validated();
        $user = $request->user();

        // Usa CreatePostAction para manter o controller enxuto e delegar lógica de negócio.
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
        $user = request()->user();
        $arr = $post->toArray();
        $myReaction = null;
        if ($user) {
            $redisKey = "post:{$post->id}:user:{$user->id}";
            try {
                $r = Redis::get($redisKey);
                if ($r !== null) $myReaction = $r;
            } catch (\Exception $e) {
                // ignora erro do Redis
            }

            if ($myReaction === null) {
                $myReaction = Reaction::where('post_id', $post->id)
                    ->where('user_id', $user->id)
                    ->value('type');
            }
        }
        $arr['my_reaction'] = $myReaction ?: null;
        return response()->json($arr);
    }

    /**
     * Renderiza a página web de post único (Inertia) em /content/{post}
     */
    public function showPage($id)
    {
        // Tenta carregar o post pelo id; se não existir, renderiza uma página de fallback
        $post = Post::with('comments.user', 'reactions.user')->find($id);

        if (! $post) {
            // Post não existe no banco — renderiza Inertia com dados de placeholder
            $placeholder = [
                'id' => $id,
                'title' => 'Publicação não encontrada',
                'content' => '<p>Esta é uma página de exemplo. O post solicitado não existe no banco de dados.</p>',
                'author' => 'Anônimo',
                'comments' => [],
                'my_reaction' => null,
            ];

            return Inertia::render('Content', [
                'post' => $placeholder,
            ]);
        }

        // post encontrado — anexa relações e my_reaction como antes
        $user = request()->user();
        $myReaction = null;
        if ($user) {
            $redisKey = "post:{$post->id}:user:{$user->id}";
            try {
                $r = Redis::get($redisKey);
                if ($r !== null) $myReaction = $r;
            } catch (\Exception $e) {
                // ignore redis error
            }

            if ($myReaction === null) {
                $myReaction = Reaction::where('post_id', $post->id)->where('user_id', $user->id)->value('type');
            }
        }

        $post->setAttribute('my_reaction', $myReaction ?: null);

        return Inertia::render('Content', [
            'post' => $post,
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
