<?php

use App\Actions\UpdatePostAction;
use App\Actions\DeletePostAction;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('UpdatePostAction atualiza campos do post', function () {
    $post = Post::factory()->create(['title' => 'Old Title']);

    $action = app(UpdatePostAction::class);
    $updated = $action->execute($post, ['title' => 'New Title']);

    $this->assertEquals('New Title', $updated->title);
    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'New Title',
    ]);
});

test('DeletePostAction deleta o post', function () {
    $post = Post::factory()->create();

    $action = app(DeletePostAction::class);
    $action->execute($post);

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});
