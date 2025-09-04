<?php

namespace App\Services;

use App\Actions\CreatePostAction;
use App\Actions\DeletePostAction;
use App\Actions\FindPostAction;
use App\Actions\GetAllPostsAction;
use App\Actions\UpdatePostAction;
use App\Models\Post;

class PostService
{
    public function create(array $data): Post
    {
        return app(CreatePostAction::class)->execute($data);
    }

    public function getAll()
    {
        return app(GetAllPostsAction::class)->execute();
    }

    public function find($id)
    {
        return app(FindPostAction::class)->execute($id);
    }

    public function update(Post $post, array $data)
    {
        return app(UpdatePostAction::class)->execute($post, $data);
    }

    public function delete(Post $post)
    {
        return app(DeletePostAction::class)->execute($post);
    }
}
