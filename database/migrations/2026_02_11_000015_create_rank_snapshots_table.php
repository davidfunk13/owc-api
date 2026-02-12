<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rank_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->nullable()->constrained()->nullOnDelete();
            $table->string('role', 20);
            $table->string('tier', 20);
            $table->unsignedSmallInteger('division')->nullable();
            $table->unsignedInteger('rank_value')->nullable();
            $table->string('snapshot_type', 20)->default('post_game');
            $table->string('season', 20)->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();
            $table->index(['user_id', 'role']);
            $table->index(['user_id', 'recorded_at']);
            $table->index('game_id');
            $table->index(['user_id', 'role', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rank_snapshots');
    }
};
