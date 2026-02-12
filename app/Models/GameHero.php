<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameHero extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'hero_id',
        'is_primary',
        'playtime_seconds',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }
}
