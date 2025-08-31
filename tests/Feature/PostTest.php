<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cria um post via API', function () {
    $payload = [
        'title' => 'Teste de Post',
        'author' => 'Tester',
        'content' => 'Conteúdo de teste',
    ];

    $response = $this->postJson('/api/posts', $payload);

    $response->assertCreated();
    $this->assertDatabaseHas('posts', [
        'title' => 'Teste de Post',
        'author' => 'Tester',
    ]);
});
