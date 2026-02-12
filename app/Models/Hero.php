<?php

namespace App\Models;

use App\Enums\Role;
use App\Enums\SubRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hero extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'role',
        'sub_role',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'role' => Role::class,
            'sub_role' => SubRole::class,
        ];
    }

    public function gameHeroes(): HasMany
    {
        return $this->hasMany(GameHero::class);
    }

    public function gamePlayerHeroes(): HasMany
    {
        return $this->hasMany(GamePlayerHero::class);
    }

    public function roundHeroes(): HasMany
    {
        return $this->hasMany(RoundHero::class);
    }
}
