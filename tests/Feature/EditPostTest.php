<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('autor edita post e campos sao atualizados incluindo is_sponsored', function () {
    $user = User::factory()->create(['name' => 'Editor']);
    $this->actingAs($user, 'sanctum');

    $post = Post::create([
        'title' => 'Titulo Original',
        'content' => 'Conteudo original',
        'author' => $user->name,
        'is_sponsored' => false,
    ]);

    $payload = [
        'title' => 'Titulo Atualizado',
        'content' => 'Conteudo alterado',
        // frontend may send camelCase field; ensure backend accepts it
        'isSponsoredContent' => true,
    ];

    $response = $this->putJson("/api/posts/{$post->id}", $payload);

    $response->assertOk();
    $response->assertJsonFragment(['title' => 'Titulo Atualizado']);

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Titulo Atualizado',
        'is_sponsored' => 1,
    ]);
});

test('edicao retorna 422 quando dados invalidos', function () {
    $user = User::factory()->create(['name' => 'Editor']);
    $this->actingAs($user, 'sanctum');

    $post = Post::create([
        'title' => 'Titulo',
        'content' => 'Conteudo',
        'author' => $user->name,
    ]);

    $response = $this->putJson("/api/posts/{$post->id}", [
        'isSponsoredContent' => 'not-a-boolean',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('isSponsoredContent');
});
