<?php

namespace App\Actions;

use App\Models\Post;

class FindPostAction
{
    public function execute($id): ?Post
    {
        return Post::find($id);
    }
}
