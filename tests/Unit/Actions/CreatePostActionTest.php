<?php

use App\Actions\CreatePostAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('CreatePostAction cria post e define author quando fornecido', function () {
    $user = User::factory()->create(['name' => 'ActionAuthor']);

    $data = [
        'title' => 'Action Title',
        'content' => 'Action content',
    ];

    $action = app(CreatePostAction::class);
    $post = $action->execute($data, $user);

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Action Title',
        'author' => 'ActionAuthor',
    ]);
});
