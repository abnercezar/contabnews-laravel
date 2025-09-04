<?php

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('apenas o autor pode editar o post', function () {
    $autor = User::factory()->create(['name' => 'Autor']);
    $outro = User::factory()->create(['name' => 'Outro']);
    $this->actingAs($autor, 'api');
    $post = Post::create([
        'title' => 'Título',
        'content' => 'Conteúdo',
        'author' => $autor->name,
    ]);

    $this->actingAs($outro, 'api');
    $response = $this->putJson("/api/posts/{$post->id}", [
        'title' => 'Novo Título',
    ]);
    $response->assertStatus(403);

    $this->actingAs($autor, 'api');
    $response = $this->putJson("/api/posts/{$post->id}", [
        'title' => 'Novo Título',
    ]);
    $response->assertOk();
    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Novo Título',
    ]);
});

test('apenas o autor pode excluir o post', function () {
    $autor = User::factory()->create(['name' => 'Autor']);
    $outro = User::factory()->create(['name' => 'Outro']);
    $this->actingAs($autor, 'api');
    $post = Post::create([
        'title' => 'Título',
        'content' => 'Conteúdo',
        'author' => $autor->name,
    ]);

    $this->actingAs($outro, 'api');
    $response = $this->deleteJson("/api/posts/{$post->id}");
    $response->assertStatus(403);

    $this->actingAs($autor, 'api');
    $response = $this->deleteJson("/api/posts/{$post->id}");
    $response->assertNoContent();
    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
    ]);
});
