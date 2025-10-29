<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    //
    protected $fillable = [
        'title',
        'coin',
        'comments',
        'author',
        'body',
        'source_url',
        'isSponsoredContent',
        'content',
        'time',
    ];

    /**
     * Comentários relacionados a um post
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Reações relacionadas a um post
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }
}
