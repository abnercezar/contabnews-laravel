<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('autor consegue apagar post e registro e removido do banco', function () {
    $autor = User::factory()->create(['name' => 'AutorDel']);
    $this->actingAs($autor, 'sanctum');

    $post = Post::create([
        'title' => 'Post para apagar',
        'content' => 'conteudo',
        'author' => $autor->name,
        'user_id' => $autor->id,
    ]);

    $response = $this->deleteJson("/api/posts/{$post->id}");
    $response->assertNoContent();

    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
    ]);
});

test('usuario que nao e autor recebe 403 ao tentar apagar', function () {
    $autor = User::factory()->create(['name' => 'AutorDel']);
    $outro = User::factory()->create(['name' => 'OutroUser']);

    $post = Post::create([
        'title' => 'Post protegido',
        'content' => 'conteudo',
        'author' => $autor->name,
        'user_id' => $autor->id,
    ]);

    $this->actingAs($outro, 'sanctum');
    $response = $this->deleteJson("/api/posts/{$post->id}");
    $response->assertStatus(403);

    $this->assertDatabaseHas('posts', ['id' => $post->id]);
});
