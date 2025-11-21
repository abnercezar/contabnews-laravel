<?php

namespace App\Actions;

use App\Models\Post;
use App\Models\User;

class CreatePostAction
{
    /**
     * Execute the creation of a Post.
     *
     * @param array $data Validated input data
     * @param \App\Models\User|null $author Optional author (will set author name)
     * @return \App\Models\Post
     */
    public function execute(array $data, ?User $author = null): Post
    {
        if ($author) {
            $data['author'] = $author->name;
            // assegura que a relação com o usuário autenticado seja registrada
            $data['user_id'] = $author->id;
        }

        return Post::create($data);
    }
}
