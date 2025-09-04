<?php

namespace App\Actions;

use App\Models\Post;

class UpdatePostAction
{
    public function execute(Post $post, array $data): Post
    {
        $post->update($data);
        return $post;
    }
}
