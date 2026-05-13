<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teacher_reflections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guru_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('evaluation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('reflection');

            $table->text('improvement_plan')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_reflections');
    }
};
