<?php

namespace App\Actions;

use App\Models\Post;

class DeletePostAction
{
    public function execute(Post $post): bool
    {
        return $post->delete();
    }
}
