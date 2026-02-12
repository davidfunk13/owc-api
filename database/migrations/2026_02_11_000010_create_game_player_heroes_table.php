<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_player_heroes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hero_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(true);
            $table->timestamps();
            $table->unique(['game_player_id', 'hero_id']);
            $table->index('hero_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_player_heroes');
    }
};
