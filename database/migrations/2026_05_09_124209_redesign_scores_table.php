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
        //
        Schema::table('scores', function (Blueprint $table) {
            $table->index('evaluation_id');

            $table->dropForeign(['sub_kriteria_id']);

            $table->dropUnique(
                'scores_evaluation_id_sub_kriteria_id_unique'
            );

            $table->dropColumn('sub_kriteria_id');

            $table->foreignId('indikator_id')
                ->after('evaluation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unique([
                'evaluation_id',
                'indikator_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
