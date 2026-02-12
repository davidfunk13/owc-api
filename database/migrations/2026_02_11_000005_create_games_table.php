<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('play_session_id')->nullable()->constrained('play_sessions')->nullOnDelete();
            $table->foreignId('map_id')->nullable()->constrained()->nullOnDelete();
            $table->string('queue_type', 30);
            $table->string('result', 10);
            $table->string('role_played', 20)->nullable();
            $table->timestamp('played_at');
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->boolean('is_placement')->default(false);
            $table->string('data_source', 20)->default('manual');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index(['user_id', 'played_at']);
            $table->index(['user_id', 'result']);
            $table->index(['user_id', 'queue_type']);
            $table->index(['user_id', 'map_id']);
            $table->index(['user_id', 'role_played']);
            $table->index('play_session_id');
            $table->index(['user_id', 'queue_type', 'result', 'map_id', 'role_played'], 'idx_games_filter');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
