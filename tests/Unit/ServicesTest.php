<?php

use App\Services\PostService;

it('post service exists and exposes expected public methods', function () {
    $serviceClass = PostService::class;

    expect(class_exists($serviceClass))->toBeTrue();

    $methods = ['create', 'getAll', 'find', 'update', 'delete'];

    foreach ($methods as $m) {
        expect(method_exists($serviceClass, $m))->toBeTrue();
    }
});
