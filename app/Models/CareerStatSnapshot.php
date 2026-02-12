<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CareerStatSnapshot extends Model
{
    use HasFactory;

    protected $attributes = [
        'source' => 'manual',
    ];

    protected $fillable = [
        'user_id',
        'hero_id',
        'queue_type',
        'stats_data',
        'captured_at',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'stats_data' => 'array',
            'captured_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }

    public function mediaAttachments(): MorphMany
    {
        return $this->morphMany(MediaAttachment::class, 'attachable');
    }
}
