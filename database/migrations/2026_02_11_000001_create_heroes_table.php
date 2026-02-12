<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('heroes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->string('role', 20);
            $table->string('sub_role', 20);
            $table->string('image_url', 255)->nullable();
            $table->timestamps();
            $table->index('role');
            $table->index('sub_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heroes');
    }
};
