<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoundHero extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_round_id',
        'hero_id',
    ];

    public function gameRound(): BelongsTo
    {
        return $this->belongsTo(GameRound::class);
    }

    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }
}
