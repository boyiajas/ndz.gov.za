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
        Schema::create('procurement_notices', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('tender'); // tender, quote
            $table->string('status')->default('open'); // open, closed
            $table->string('financial_year')->default('2025/2026');
            $table->string('reference_no')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('closing_date')->nullable();
            $table->string('briefing_date')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('document_url', 2048)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_notices');
    }
};
