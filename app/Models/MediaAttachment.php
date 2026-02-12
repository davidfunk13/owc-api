<?php

namespace App\Models;

use App\Enums\MediaType;
use App\Enums\ProcessingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MediaAttachment extends Model
{
    use HasFactory;

    protected $attributes = [
        'processing_status' => 'pending',
    ];

    protected $fillable = [
        'user_id',
        'attachable_id',
        'attachable_type',
        'media_type',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'ocr_data',
        'parsed_data',
        'processing_status',
        'processed_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'media_type' => MediaType::class,
            'processing_status' => ProcessingStatus::class,
            'ocr_data' => 'array',
            'parsed_data' => 'array',
            'metadata' => 'array',
            'processed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
