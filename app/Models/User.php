<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sub',
        'battlenet_id',
        'battletag',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    public function playSessions(): HasMany
    {
        return $this->hasMany(PlaySession::class);
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function customStatDefinitions(): HasMany
    {
        return $this->hasMany(CustomStatDefinition::class);
    }

    public function rankSnapshots(): HasMany
    {
        return $this->hasMany(RankSnapshot::class);
    }

    public function heroSrSnapshots(): HasMany
    {
        return $this->hasMany(HeroSrSnapshot::class);
    }

    public function careerStatSnapshots(): HasMany
    {
        return $this->hasMany(CareerStatSnapshot::class);
    }

    public function mediaAttachments(): HasMany
    {
        return $this->hasMany(MediaAttachment::class);
    }
}
