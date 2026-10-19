<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procurement_notices', function (Blueprint $table) {
            $table->string('category')->default('Open Tenders')->after('type');
            $table->unsignedInteger('download_count')->default(0)->after('document_url');
            $table->string('file_name')->nullable()->after('download_count');
            $table->string('file_size')->nullable()->after('file_name');
            $table->dropUnique(['reference_no']);
        });

        Schema::table('procurement_notices', function (Blueprint $table) {
            $table->string('reference_no')->nullable()->change();
            $table->index('reference_no');
            $table->index('financial_year');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::table('procurement_notices', function (Blueprint $table) {
            $table->dropIndex(['reference_no']);
            $table->dropIndex(['financial_year']);
            $table->dropIndex(['category']);
            $table->unique('reference_no');
            $table->dropColumn(['category', 'download_count', 'file_name', 'file_size']);
        });
    }
};
