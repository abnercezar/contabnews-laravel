<?php

namespace App\Actions;

use App\Models\Post;

class GetAllPostsAction
{
    public function execute()
    {
        return Post::all();
    }
}
