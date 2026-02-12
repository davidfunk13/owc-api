<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomStatDefinition extends Model
{
    use HasFactory;

    protected $attributes = [
        'data_type' => 'integer',
        'sort_order' => 0,
        'is_active' => true,
    ];

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'data_type',
        'unit',
        'min_value',
        'max_value',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_value' => 'decimal:2',
            'max_value' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gameCustomStats(): HasMany
    {
        return $this->hasMany(GameCustomStat::class);
    }
}
