<?php

use App\Actions\UpdateClassifiedAction;
use App\Actions\DeleteClassifiedAction;
use App\Models\Classified;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('UpdateClassifiedAction atualiza campos do classified', function () {
    $classified = Classified::factory()->create(['title' => 'Old Classified']);

    $action = app(UpdateClassifiedAction::class);
    $updated = $action->execute($classified, ['title' => 'New Classified']);

    $this->assertEquals('New Classified', $updated->title);
    $this->assertDatabaseHas('classifieds', [
        'id' => $classified->id,
        'title' => 'New Classified',
    ]);
});

test('DeleteClassifiedAction deleta o classified', function () {
    $classified = Classified::factory()->create();

    $action = app(DeleteClassifiedAction::class);
    $action->execute($classified);

    $this->assertDatabaseMissing('classifieds', ['id' => $classified->id]);
});
