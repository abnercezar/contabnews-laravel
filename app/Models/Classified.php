<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classified extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'author',
        'body',
        'source_url',
        'is_sponsored',
        'comments',
        'time',
    ];

    protected $casts = [
        'is_sponsored' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
