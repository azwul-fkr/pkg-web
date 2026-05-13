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
        Schema::create('self_assessments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guru_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('period_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('status', [
                'draft',
                'submitted',
                'finalized'
            ])->default('draft');

            $table->timestamps();

            $table->unique([
                'guru_id',
                'period_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_assessments');
    }
};
