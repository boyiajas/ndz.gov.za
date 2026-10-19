<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('department_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'department_id']);
        });

        // Migrate existing single department_id values into the pivot table
        if (Schema::hasColumn('users', 'department_id')) {
            $existingUsers = DB::table('users')->whereNotNull('department_id')->get(['id', 'department_id']);
            foreach ($existingUsers as $u) {
                DB::table('department_user')->updateOrInsert(
                    ['user_id' => $u->id, 'department_id' => $u->department_id],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_user');
    }
};
