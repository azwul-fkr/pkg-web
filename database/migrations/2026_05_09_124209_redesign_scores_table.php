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

            $table->dropForeign(['sub_kriteria_id']);

            $table->dropColumn('sub_kriteria_id');

            $table->foreignId('indikator_id')
                ->after('evaluation_id')
                ->constrained()
                ->cascadeOnDelete();
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
