<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('map_submaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('map_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->string('image_url', 255)->nullable();
            $table->timestamps();
            $table->unique(['map_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_submaps');
    }
};
