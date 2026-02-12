<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->string('team_side', 10);
            $table->string('role', 20)->nullable();
            $table->string('player_name', 100)->nullable();
            $table->unsignedSmallInteger('slot_number')->nullable();
            $table->timestamps();
            $table->index('game_id');
            $table->index(['game_id', 'team_side']);
            $table->index(['game_id', 'team_side', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_players');
    }
};
