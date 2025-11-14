<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'title',
        'coin',
        'comments',
        'author',
        'body',
        'source_url',
        'isSponsoredContent',
        'is_sponsored',
        'content',
        'time',
    ];

    protected $casts = [
        'is_sponsored' => 'boolean',
    ];

    // permite usar $post->isSponsoredContent e mapear para coluna is_sponsored
    public function getIsSponsoredContentAttribute()
    {
        // Eloquent normaliza nomes, então verifica a coluna snake_case
        return (bool) ($this->attributes['is_sponsored'] ?? false);
    }

    public function setIsSponsoredContentAttribute($value)
    {
        $this->attributes['is_sponsored'] = (bool) $value;
    }

    // adiciona contadores computados de reações ao modelo serializado
    protected $appends = ['tabcoins', 'tabcashs'];

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

    // número de upvotes (usa contador armazenado se disponível)
    public function getTabcoinsAttribute()
    {
        if (array_key_exists('upvotes_count', $this->attributes)) {
            return (int) $this->attributes['upvotes_count'];
        }
        return $this->reactions()->where('type', 'up')->count();
    }

    // upvotes líquidos (upvotes menos downvotes) — usa contadores armazenados se disponíveis
    public function getTabcashsAttribute()
    {
        if (array_key_exists('upvotes_count', $this->attributes) || array_key_exists('downvotes_count', $this->attributes)) {
            $up = (int) ($this->attributes['upvotes_count'] ?? 0);
            $down = (int) ($this->attributes['downvotes_count'] ?? 0);
            return $up - $down;
        }
        $up = $this->reactions()->where('type', 'up')->count();
        $down = $this->reactions()->where('type', 'down')->count();
        return $up - $down;
    }
}
