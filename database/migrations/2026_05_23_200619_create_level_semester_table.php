<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('level_semester', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->integer('order')->default(1); // 1 or 2 within the level
            $table->timestamps();

            $table->unique(['classe_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('level_semester');
    }
};
