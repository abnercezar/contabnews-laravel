<?php

namespace App\Actions;

use App\Services\PostService;

class CreatePostAction
{
    protected $service;

    public function __construct(PostService $service)
    {
        $this->service = $service;
    }

    public function execute(array $data)
    {
        return $this->service->create($data);
    }
}
