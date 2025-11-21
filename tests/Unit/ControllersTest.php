<?php

$controllers = [
    App\Http\Controllers\PostController::class,
    App\Http\Controllers\ClassifiedController::class,
    App\Http\Controllers\ReactionController::class,
    App\Http\Controllers\AuthController::class,
    App\Http\Controllers\UserRegisterController::class,
    App\Http\Controllers\Controller::class,
    // API controllers
    App\Http\Controllers\Api\PostController::class,
    App\Http\Controllers\Api\ClassifiedController::class,
    App\Http\Controllers\Api\CommentController::class,
];

it('all controllers exist and have at least one public method', function () use ($controllers) {
    foreach ($controllers as $class) {
        expect(class_exists($class))->toBeTrue();

        $ref = new \ReflectionClass($class);
        $publicMethods = array_filter($ref->getMethods(\ReflectionMethod::IS_PUBLIC), function ($m) use ($class) {
            return $m->class === $class && $m->name !== '__construct';
        });

        expect(count($publicMethods))->toBeGreaterThan(0);
    }
});
