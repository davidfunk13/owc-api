<?php

namespace App\Models;

use App\Enums\Role;
use App\Enums\TeamSide;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GamePlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'team_side',
        'role',
        'player_name',
        'slot_number',
    ];

    protected function casts(): array
    {
        return [
            'team_side' => TeamSide::class,
            'role' => Role::class,
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function gamePlayerHeroes(): HasMany
    {
        return $this->hasMany(GamePlayerHero::class);
    }
}
