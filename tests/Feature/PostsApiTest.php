<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('lista posts publicamente com paginação', function () {
    Post::factory()->count(5)->create();

    $response = $this->getJson('/api/posts?per_page=2&page=1');

    $response->assertOk();
    $data = $response->json();
    // Controller returns items() array for the page
    expect(is_array($data))->toBeTrue();
    expect(count($data))->toBe(2);
});

test('mostra um post específico', function () {
    $post = Post::factory()->create(['title' => 'Titulo Teste']);

    $response = $this->getJson("/api/posts/{$post->id}");

    $response->assertOk()
        ->assertJsonFragment(['title' => 'Titulo Teste']);
});

test('criar post valida campos obrigatorios', function () {
    $user = User::factory()->create(['name' => 'AutorTeste']);
    $this->actingAs($user, 'sanctum');

    $response = $this->postJson('/api/posts', [
        'content' => 'Sem title',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('title');
});

test('criar post atribui autor do usuário autenticado', function () {
    $user = User::factory()->create(['name' => 'Creator']);
    $this->actingAs($user, 'sanctum');

    $payload = [
        'title' => 'Criado por action',
        'content' => 'Conteúdo',
    ];

    $response = $this->postJson('/api/posts', $payload);

    $response->assertCreated();
    $this->assertDatabaseHas('posts', [
        'title' => 'Criado por action',
        'author' => 'Creator',
    ]);
});
