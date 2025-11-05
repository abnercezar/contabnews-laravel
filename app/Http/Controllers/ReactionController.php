<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reaction;
use App\Models\Post;
use Illuminate\Support\Facades\Redis;

class ReactionController extends Controller
{
    /**
     * Alterna uma reação usando script atômico no Redis e enfileira evento para persistência.
     *
     * Espera JSON { type: 'up'|'down' }
     */
    public function toggle(Request $request, Post $post)
    {
        $data = $request->validate([
            'type' => 'required|string|in:up,down',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Chaves do Redis
        $postId = $post->id;
        $userId = $user->id;
        $userKey = "post:{$postId}:user:{$userId}";
        $upKey = "post:{$postId}:upvotes";
        $downKey = "post:{$postId}:downvotes";
        $eventList = "posts:events";

        // payload do evento para persistir depois (o worker irá interpretar)
        $event = json_encode([
            'post_id' => $postId,
            'user_id' => $userId,
            'type' => $data['type'],
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Script Lua para atualizar atomicaamente a reação por usuário e os contadores, e empurrar o evento
        $lua = <<<'LUA'
local user_key, up_key, down_key, event_list = KEYS[1], KEYS[2], KEYS[3], KEYS[4]
local new_type = ARGV[1]
local event_json = ARGV[2]
local current = redis.call('GET', user_key)
if not current then
    redis.call('SET', user_key, new_type)
    if new_type == 'up' then
        redis.call('INCR', up_key)
    else
        redis.call('INCR', down_key)
    end
    redis.call('LPUSH', event_list, event_json)
else
    if current == new_type then
        redis.call('DEL', user_key)
        if new_type == 'up' then
            redis.call('DECR', up_key)
        else
            redis.call('DECR', down_key)
        end
        redis.call('LPUSH', event_list, event_json)
    else
        if current == 'up' then
            redis.call('DECR', up_key)
            redis.call('INCR', down_key)
        else
            redis.call('DECR', down_key)
            redis.call('INCR', up_key)
        end
        redis.call('SET', user_key, new_type)
        redis.call('LPUSH', event_list, event_json)
    end
end
local up = tonumber(redis.call('GET', up_key) or '0')
local down = tonumber(redis.call('GET', down_key) or '0')
return {up, up - down}
LUA;

        // Executa o script
        try {
            $res = Redis::eval($lua, 4, $userKey, $upKey, $downKey, $eventList, $data['type'], $event);
        } catch (\Exception $e) {
            // fallback para caminho via BD se o Redis não estiver disponível
            return response()->json(['message' => 'Redis error', 'error' => $e->getMessage()], 500);
        }

        // $res é array [up, net]
        return response()->json([
            'tabcoins' => intval($res[0] ?? 0),
            'tabcashs' => intval($res[1] ?? 0),
        ]);
    }
}
