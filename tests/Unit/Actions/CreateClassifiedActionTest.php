<?php

use App\Actions\CreateClassifiedAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('CreateClassifiedAction cria classified e define author quando fornecido', function () {
    $user = User::factory()->create(['name' => 'ClassAuthor']);

    $data = [
        'title' => 'Classified Title',
        'body' => 'Classified body content',
    ];

    $action = app(CreateClassifiedAction::class);
    $classified = $action->execute($data, $user);

    $this->assertDatabaseHas('classifieds', [
        'id' => $classified->id,
        'title' => 'Classified Title',
        'author' => 'ClassAuthor',
    ]);
});
