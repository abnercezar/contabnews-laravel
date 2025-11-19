<?php

namespace App\Http\Controllers;

use App\Models\Classified;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassifiedController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 50);
        $page = (int) $request->get('page', 1);

        $items = Classified::orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->items();

        // Sanitiza user quando presente
        $data = array_map(function ($item) {
            if (isset($item['user']) && is_array($item['user'])) {
                $item['user'] = [
                    'id' => $item['user']['id'] ?? null,
                    'name' => $item['user']['name'] ?? null,
                ];
            }
            return $item;
        }, array_map(function ($m) {
            return is_array($m) ? $m : $m->toArray();
        }, $items));

        return response()->json($data);
    }

    public function show($id)
    {
        $classified = Classified::find($id);
        if (! $classified) return response()->json(null, 404);
        $arr = $classified->toArray();
        if (isset($arr['user']) && is_array($arr['user'])) {
            $arr['user'] = [
                'id' => $arr['user']['id'] ?? null,
                'name' => $arr['user']['name'] ?? null,
            ];
        }
        return response()->json($arr);
    }

    /**
     * Renderiza a página web de um classificado (Inertia) em /classifieds/{id}
     */
    public function showPage($id)
    {
        $classified = Classified::find($id);

        if (! $classified) {
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

        $arr = $classified->toArray();
        // normalize fields expected by Content.vue
        $arr['content'] = $arr['content'] ?? $arr['body'] ?? '';
        $arr['comments'] = $arr['comments'] ?? [];
        $arr['my_reaction'] = null;

        return Inertia::render('Content', [
            'post' => $arr,
        ]);
    }
}
