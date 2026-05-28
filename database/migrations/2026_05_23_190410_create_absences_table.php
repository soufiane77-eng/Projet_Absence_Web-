<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('marked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('absent'); // present, absent, justified, late
            $table->boolean('is_justified')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'seance_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
