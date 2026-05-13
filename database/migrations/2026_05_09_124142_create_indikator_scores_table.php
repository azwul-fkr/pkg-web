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
        Schema::create('indikator_scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('indikator_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('score');
            // 1-4 atau 1-5

            $table->text('description');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikator_scores');
    }
};
