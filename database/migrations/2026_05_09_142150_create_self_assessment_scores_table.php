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
        Schema::create('self_assessment_scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('self_assessment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('indikator_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('nilai');

            $table->text('comment')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_assessment_scores');
    }
};
