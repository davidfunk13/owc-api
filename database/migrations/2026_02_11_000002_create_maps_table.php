<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maps', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->string('map_type', 20);
            $table->string('image_url', 255)->nullable();
            $table->timestamps();
            $table->index('map_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maps');
    }
};
