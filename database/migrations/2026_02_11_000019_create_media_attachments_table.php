<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('attachable_id')->nullable();
            $table->string('attachable_type', 100)->nullable();
            $table->string('media_type', 30);
            $table->string('file_path', 500);
            $table->string('original_filename', 255)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedInteger('file_size')->nullable();
            $table->json('ocr_data')->nullable();
            $table->json('parsed_data')->nullable();
            $table->string('processing_status', 20)->default('pending');
            $table->timestamp('processed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index(['attachable_type', 'attachable_id']);
            $table->index(['user_id', 'media_type']);
            $table->index('processing_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_attachments');
    }
};
