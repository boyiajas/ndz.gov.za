<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleBasedUserSeeder extends Seeder
{
    /**
     * Seed role-based portal users.
     */
    public function run(): void
    {
        $password = Hash::make('Password123!');

        $users = [
            [
                'name' => 'NDZ Administrator',
                'email' => 'admin@ndz.gov.za',
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Municipal Manager',
                'email' => 'manager@ndz.gov.za',
                'role' => User::ROLE_MANAGER,
            ],
            [
                'name' => 'Content Editor',
                'email' => 'editor@ndz.gov.za',
                'role' => User::ROLE_EDITOR,
            ],
            [
                'name' => 'Citizen User',
                'email' => 'citizen@ndz.gov.za',
                'role' => User::ROLE_CITIZEN,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    ...$user,
                    'password' => $password,
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
