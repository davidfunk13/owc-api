<?php

namespace App\Models;

use App\Enums\MapType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Map extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'map_type',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'map_type' => MapType::class,
        ];
    }

    public function submaps(): HasMany
    {
        return $this->hasMany(MapSubmap::class);
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
}
