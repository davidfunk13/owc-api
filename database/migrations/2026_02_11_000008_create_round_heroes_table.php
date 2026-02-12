<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('round_heroes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_round_id')->constrained('game_rounds')->cascadeOnDelete();
            $table->foreignId('hero_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['game_round_id', 'hero_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('round_heroes');
    }
};
