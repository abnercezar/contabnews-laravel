<?php

use App\Actions\FindPostAction;
use App\Actions\GetAllPostsAction;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('FindPostAction encontra post criado', function () {
    $post = Post::factory()->create(['title' => 'Findable']);

    $action = app(FindPostAction::class);
    $found = $action->execute($post->id);

    expect($found)->not->toBeNull();
    $this->assertEquals('Findable', $found->title);
});

test('GetAllPostsAction retorna coleção de posts', function () {
    Post::factory()->count(3)->create();

    $action = app(GetAllPostsAction::class);
    $all = $action->execute();

    $this->assertCount(3, $all);
});
