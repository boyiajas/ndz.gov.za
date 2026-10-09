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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('reference_no')->nullable()->index();
            $table->string('department')->index();
            $table->string('status', 20)->default('open')->index();
            $table->dateTime('closing_date')->nullable();
            $table->string('remuneration')->nullable();
            $table->string('location')->default('Creighton Main Office');
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->string('document_url', 2048)->nullable();
            $table->string('document_name')->nullable();
            $table->string('application_url', 2048)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
