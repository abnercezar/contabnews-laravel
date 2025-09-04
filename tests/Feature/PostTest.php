<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cria um post via API', function () {
    $user = User::factory()->create(['name' => 'Tester']);
    $this->actingAs($user, 'api');

    $payload = [
        'title' => 'Teste de Post',
        'content' => 'Conteúdo de teste',
    ];

    $response = $this->postJson('/api/posts', $payload);

    $response->assertCreated();
    $this->assertDatabaseHas('posts', [
        'title' => 'Teste de Post',
        'author' => 'Tester',
    ]);
});
