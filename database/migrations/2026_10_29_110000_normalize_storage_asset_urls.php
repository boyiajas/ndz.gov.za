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
        $targets = [
            'document_categories' => 'image_url',
            'articles' => 'image_url',
            'gallery_items' => 'image_url',
            'procurement_notices' => 'document_url',
            'vacancies' => 'document_url',
            'documents' => 'file_url',
        ];

        foreach ($targets as $table => $column) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
                continue;
            }

            DB::table($table)
                ->whereNotNull($column)
                ->get(['id', $column])
                ->each(function ($row) use ($table, $column) {
                    $val = $row->{$column};
                    if (is_string($val) && preg_match('#^https?://(?:localhost|127\.0\.0\.1)(?::\d+)?(/storage/.*)$#i', $val, $matches)) {
                        DB::table($table)
                            ->where('id', $row->id)
                            ->update([$column => $matches[1]]);
                    }
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversible normalization is a no-op
    }
};
