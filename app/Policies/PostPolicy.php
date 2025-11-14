<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

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
        // prefer id-based check when available
        if ($post->user_id) {
            return $user->id === $post->user_id;
        }
        return $post->author === $user->name;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Post $post): bool
    {
        if (!$user) return false;
        if ($post->user_id) {
            return $user->id === $post->user_id;
        }
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
