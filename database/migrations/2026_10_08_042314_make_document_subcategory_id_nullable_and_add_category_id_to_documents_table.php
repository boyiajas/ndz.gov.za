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
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'document_category_id')) {
                $table->foreignId('document_category_id')->nullable()->after('id')->constrained('document_categories')->cascadeOnDelete();
            }
            $table->foreignId('document_subcategory_id')->nullable()->change();
        });

        // Backfill document_category_id from existing subcategories
        $subcategories = \Illuminate\Support\Facades\DB::table('document_subcategories')
            ->pluck('document_category_id', 'id');
        foreach ($subcategories as $subId => $catId) {
            \Illuminate\Support\Facades\DB::table('documents')
                ->where('document_subcategory_id', $subId)
                ->whereNull('document_category_id')
                ->update(['document_category_id' => $catId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['document_category_id']);
            $table->dropColumn('document_category_id');
            $table->foreignId('document_subcategory_id')->nullable(false)->change();
        });
    }
};
