<?php

use App\Actions\CreateClassifiedAction;
use App\Actions\CreatePostAction;
use App\Actions\DeleteClassifiedAction;
use App\Actions\DeletePostAction;
use App\Actions\FindPostAction;
use App\Actions\GetAllPostsAction;
use App\Actions\UpdateClassifiedAction;
use App\Actions\UpdatePostAction;

it('all actions classes exist and expose an execute method', function () {
    $actions = [
        CreateClassifiedAction::class => 'execute',
        CreatePostAction::class => 'execute',
        DeleteClassifiedAction::class => 'execute',
        DeletePostAction::class => 'execute',
        FindPostAction::class => 'execute',
        GetAllPostsAction::class => 'execute',
        UpdateClassifiedAction::class => 'execute',
        UpdatePostAction::class => 'execute',
    ];

    foreach ($actions as $class => $method) {
        expect(class_exists($class))->toBeTrue();
        expect(method_exists($class, $method))->toBeTrue();
    }
});
