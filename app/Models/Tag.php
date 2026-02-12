<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'color',
        'icon',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function games(): MorphToMany
    {
        return $this->morphedByMany(Game::class, 'taggable');
    }

    public function playSessions(): MorphToMany
    {
        return $this->morphedByMany(PlaySession::class, 'taggable');
    }

    public function gameRounds(): MorphToMany
    {
        return $this->morphedByMany(GameRound::class, 'taggable');
    }
}
