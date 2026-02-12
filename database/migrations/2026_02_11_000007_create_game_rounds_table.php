<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('round_number');
            $table->foreignId('map_submap_id')->nullable()->constrained('map_submaps')->nullOnDelete();
            $table->string('result', 10)->nullable();
            $table->string('side', 20)->nullable();
            $table->unsignedSmallInteger('score_team')->nullable();
            $table->unsignedSmallInteger('score_enemy')->nullable();
            $table->timestamps();
            $table->unique(['game_id', 'round_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_rounds');
    }
};
