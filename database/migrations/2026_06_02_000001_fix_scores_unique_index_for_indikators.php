<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('scores')) {
            return;
        }

        $this->dropIndexIfExists(
            'scores',
            'scores_evaluation_id_sub_kriteria_id_unique'
        );

        if (
            Schema::hasColumn('scores', 'evaluation_id') &&
            Schema::hasColumn('scores', 'indikator_id') &&
            !$this->indexExists('scores', 'scores_evaluation_id_indikator_id_unique')
        ) {
            DB::statement(
                'ALTER TABLE `scores` ADD UNIQUE `scores_evaluation_id_indikator_id_unique` (`evaluation_id`, `indikator_id`)'
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->dropIndexIfExists(
            'scores',
            'scores_evaluation_id_indikator_id_unique'
        );
    }

    private function indexExists(string $table, string $index): bool
    {
        return collect(
            DB::select('SHOW INDEX FROM `' . $table . '` WHERE Key_name = ?', [$index])
        )->isNotEmpty();
    }

    private function dropIndexIfExists(string $table, string $index): void
    {
        if ($this->indexExists($table, $index)) {
            if (
                $index === 'scores_evaluation_id_sub_kriteria_id_unique' &&
                !$this->indexExists($table, 'scores_evaluation_id_index')
            ) {
                DB::statement(
                    'ALTER TABLE `' . $table . '` ADD INDEX `scores_evaluation_id_index` (`evaluation_id`)'
                );
            }

            DB::statement(
                'ALTER TABLE `' . $table . '` DROP INDEX `' . $index . '`'
            );
        }
    }
};
