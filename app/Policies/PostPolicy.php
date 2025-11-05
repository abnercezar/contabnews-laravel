<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can create posts.
     */
    public function create(?User $user): bool
    {
    return $user !== null; // apenas usuários autenticados podem criar
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Post $post): bool
    {
        if (!$user) return false;
        return $post->author === $user->name;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Post $post): bool
    {
        if (!$user) return false;
        return $post->author === $user->name;
    }

    /**
     * Determine whether the user can reply to a post.
     */
    public function reply(?User $user, Post $post): bool
    {
        return $user !== null;
    }
}
