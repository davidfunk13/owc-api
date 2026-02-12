<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_custom_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->foreignId('custom_stat_definition_id')->constrained()->cascadeOnDelete();
            $table->decimal('numeric_value', 12, 2);
            $table->timestamps();
            $table->unique(['game_id', 'custom_stat_definition_id'], 'uniq_game_custom_stats');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_custom_stats');
    }
};
