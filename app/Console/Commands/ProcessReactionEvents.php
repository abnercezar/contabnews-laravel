<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Models\Reaction;
use App\Models\Post;

class ProcessReactionEvents extends Command
{
    protected $signature = 'reactions:process {--timeout=0}';
    protected $description = 'Process reaction events from Redis list and persist to DB';

    public function handle()
    {
        $this->info('Starting reaction events worker...');
        $timeout = (int) $this->option('timeout');

        while (true) {
            // BRPOP retorna [lista, valor] ou null
            $res = Redis::brpop('posts:events', $timeout);
            if (!$res) {
                if ($timeout > 0) {
                    $this->info('Timeout reached, exiting.');
                    return 0;
                }
                continue;
            }

            $payload = $res[1] ?? null;
            if (!$payload) continue;

            try {
                $event = json_decode($payload, true);
                if (!$event || !isset($event['post_id']) || !isset($event['user_id']) || !isset($event['type'])) {
                    continue;
                }

                $postId = $event['post_id'];
                $userId = $event['user_id'];
                $newType = $event['type'];

                \DB::transaction(function () use ($postId, $userId, $newType) {
                    // encontra reação existente
                    $existing = Reaction::where('post_id', $postId)->where('user_id', $userId)->first();

                    if (!$existing) {
                        // cria se não existe
                        Reaction::create(['post_id' => $postId, 'user_id' => $userId, 'type' => $newType]);
                    } else {
                        if ($existing->type === $newType) {
                            // usuário desativou no Redis; remove a reação
                            $existing->delete();
                        } else {
                            // altera o tipo
                            $existing->type = $newType;
                            $existing->save();
                        }
                    }

                    // persiste contadores do Redis para o BD para o post
                    $upKey = "post:{$postId}:upvotes";
                    $downKey = "post:{$postId}:downvotes";
                    $up = intval(Redis::get($upKey) ?? 0);
                    $down = intval(Redis::get($downKey) ?? 0);
                    Post::where('id', $postId)->update([
                        'upvotes_count' => $up,
                        'downvotes_count' => $down,
                    ]);
                });
            } catch (\Exception $e) {
                $this->error('Failed to process event: ' . $e->getMessage());
                // continue
            }
        }

        return 0;
    }
}
