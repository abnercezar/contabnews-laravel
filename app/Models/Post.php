<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
