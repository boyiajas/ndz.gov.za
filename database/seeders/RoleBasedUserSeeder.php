<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleBasedUserSeeder extends Seeder
{
    /**
     * Seed role-based portal users assigned to their respective departments.
     */
    public function run(): void
    {
        $password = Hash::make('Password123!');

        $departmentsByCode = Department::all()->keyBy('code');

        $users = [
            [
                'name' => 'NDZ Administrator',
                'email' => 'admin@ndz.gov.za',
                'role' => User::ROLE_ADMIN,
                'department_code' => null, // Global access across all departments
            ],
            [
                'name' => 'Municipal Manager',
                'email' => 'manager@ndz.gov.za',
                'role' => User::ROLE_MANAGER,
                'department_code' => 'MM',
            ],
            [
                'name' => 'Budget & Treasury Officer',
                'email' => 'bto@ndz.gov.za',
                'role' => User::ROLE_EDITOR,
                'department_code' => 'BTO',
            ],
            [
                'name' => 'Corporate Services Officer',
                'email' => 'corporate@ndz.gov.za',
                'role' => User::ROLE_EDITOR,
                'department_code' => 'CORP',
            ],
            [
                'name' => 'Community Services Officer',
                'email' => 'community@ndz.gov.za',
                'role' => User::ROLE_EDITOR,
                'department_code' => 'COMM',
            ],
            [
                'name' => 'Public Works Officer',
                'email' => 'publicworks@ndz.gov.za',
                'role' => User::ROLE_EDITOR,
                'department_code' => 'PW',
            ],
            [
                'name' => 'Town Planning Officer',
                'email' => 'townplanning@ndz.gov.za',
                'role' => User::ROLE_EDITOR,
                'department_code' => 'DTPS',
            ],
            [
                'name' => 'Content & Media Editor',
                'email' => 'editor@ndz.gov.za',
                'role' => User::ROLE_EDITOR,
                'department_code' => 'MM',
            ],
            [
                'name' => 'Citizen User',
                'email' => 'citizen@ndz.gov.za',
                'role' => User::ROLE_CITIZEN,
                'department_code' => null,
            ],
        ];

        foreach ($users as $userData) {
            $deptId = null;
            if ($userData['department_code'] && isset($departmentsByCode[$userData['department_code']])) {
                $deptId = $departmentsByCode[$userData['department_code']]->id;
            }

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'role' => $userData['role'],
                    'department_id' => $deptId,
                    'password' => $password,
                    'email_verified_at' => now(),
                ]
            );

            if ($deptId) {
                $user->departments()->sync([$deptId]);
            } else {
                $user->departments()->detach();
            }
        }
    }
}
