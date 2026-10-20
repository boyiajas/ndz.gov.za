<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sampleEmails = [
            'admin@ndz.gov.za',
            'manager@ndz.gov.za',
            'bto@ndz.gov.za',
            'corporate@ndz.gov.za',
            'community@ndz.gov.za',
            'publicworks@ndz.gov.za',
            'townplanning@ndz.gov.za',
            'editor@ndz.gov.za',
            'citizen@ndz.gov.za',
        ];

        User::whereIn('email', $sampleEmails)->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed for removed sample users
    }
};
