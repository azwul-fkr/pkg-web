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
        Schema::table('evidences', function (Blueprint $table) {
            //
            $table->foreignId('kriteria_id')
                ->nullable()
                ->after('guru_id')
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('sub_kriteria_id')
                ->nullable()
                ->after('kriteria_id')
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('indikator_id')
                ->nullable()
                ->after('sub_kriteria_id')
                ->constrained()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evidences', function (Blueprint $table) {
            //
        });
    }
};
