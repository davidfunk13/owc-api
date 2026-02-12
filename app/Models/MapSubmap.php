<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MapSubmap extends Model
{
    use HasFactory;

    protected $fillable = [
        'map_id',
        'name',
        'slug',
        'image_url',
    ];

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

    public function gameRounds(): HasMany
    {
        return $this->hasMany(GameRound::class);
    }
}
