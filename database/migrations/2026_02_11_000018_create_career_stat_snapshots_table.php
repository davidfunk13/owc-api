<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_stat_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hero_id')->nullable()->constrained()->nullOnDelete();
            $table->string('queue_type', 30)->nullable();
            $table->json('stats_data');
            $table->timestamp('captured_at');
            $table->string('source', 50)->default('manual');
            $table->timestamps();
            $table->index('user_id');
            $table->index(['user_id', 'captured_at']);
            $table->index(['user_id', 'hero_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_stat_snapshots');
    }
};
