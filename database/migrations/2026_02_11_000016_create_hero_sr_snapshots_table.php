<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_sr_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hero_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('sr_value');
            $table->string('snapshot_type', 20)->default('post_game');
            $table->string('season', 20)->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();
            $table->index(['user_id', 'hero_id', 'recorded_at']);
            $table->index('game_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_sr_snapshots');
    }
};
