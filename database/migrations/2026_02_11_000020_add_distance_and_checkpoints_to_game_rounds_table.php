<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_rounds', function (Blueprint $table) {
            $table->decimal('distance_meters', 8, 2)->nullable()->after('score_enemy');
            $table->unsignedSmallInteger('checkpoints_reached')->nullable()->after('distance_meters');
            $table->boolean('is_overtime')->default(false)->after('checkpoints_reached');
        });
    }

    public function down(): void
    {
        Schema::table('game_rounds', function (Blueprint $table) {
            $table->dropColumn(['distance_meters', 'checkpoints_reached', 'is_overtime']);
        });
    }
};
