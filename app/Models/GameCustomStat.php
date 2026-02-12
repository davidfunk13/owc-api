<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameCustomStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'custom_stat_definition_id',
        'numeric_value',
    ];

    protected function casts(): array
    {
        return [
            'numeric_value' => 'decimal:2',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function customStatDefinition(): BelongsTo
    {
        return $this->belongsTo(CustomStatDefinition::class);
    }
}
