<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_module', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20)->default('cours'); // cours, td, tp
            $table->timestamps();

            $table->unique(['user_id', 'module_id', 'class_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_module');
    }
};
