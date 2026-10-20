<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MunicipalStaffUserSeeder extends Seeder
{
    /**
     * Seed official municipal staff users and assign their respective roles and departments.
     */
    public function run(): void
    {
        $defaultPassword = env('DEFAULT_STAFF_PASSWORD', 'Password123!');
        $hashedPassword = Hash::make($defaultPassword);

        // Ensure departments exist first
        if (Department::count() === 0) {
            $this->call(DepartmentSeeder::class);
        }

        $departmentsByCode = Department::all()->keyBy('code');

        // Purge legacy sample / dummy login accounts
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

        /**
         * Staff configuration:
         * 1. Mfuneko Spengane (spenganem@ndz.gov.za) - IT Admin / Corporate Support Services
         * 2. Mthokozisi Nxele (nxelem@ndz.gov.za) - IT Systems / Web Notices & Administration
         * 3. Siyabonga Mshengu (mshengus@ndz.gov.za) - Corporate Projects & Infrastructure Manager
         * 4. Buhlebakhe Sokhela (sokhelab@ndz.gov.za) - Corporate & Community Services Manager
         * 5. Skhumbuzo Sethuntsa (communications@ndz.gov.za & sikhumbuzosethuntsa@gmail.com) - Head of Communications
         * 6. Nqobile Vakalisa (vakalisan@ndz.gov.za) - Municipal Spokesperson / Communications Manager
         */
        $staffMembers = [
            [
                'name' => 'Mfuneko Spengane',
                'email' => 'spenganem@ndz.gov.za',
                'role' => User::ROLE_ADMIN,
                'department_codes' => ['CORP'],
                'primary_dept' => 'CORP',
            ],
            [
                'name' => 'Mthokozisi Nxele',
                'email' => 'nxelem@ndz.gov.za',
                'role' => User::ROLE_ADMIN,
                'department_codes' => ['CORP', 'MM'],
                'primary_dept' => 'CORP',
            ],
            [
                'name' => 'Siyabonga Mshengu',
                'email' => 'mshengus@ndz.gov.za',
                'role' => User::ROLE_MANAGER,
                'department_codes' => ['CORP', 'PW'],
                'primary_dept' => 'CORP',
            ],
            [
                'name' => 'Buhlebakhe Sokhela',
                'email' => 'sokhelab@ndz.gov.za',
                'role' => User::ROLE_MANAGER,
                'department_codes' => ['CORP', 'COMM'],
                'primary_dept' => 'CORP',
            ],
            [
                'name' => 'Skhumbuzo Sethuntsa',
                'email' => 'communications@ndz.gov.za',
                'role' => User::ROLE_MANAGER,
                'department_codes' => ['MM', 'CORP'],
                'primary_dept' => 'MM',
            ],
            [
                'name' => 'Skhumbuzo Sethuntsa',
                'email' => 'sikhumbuzosethuntsa@gmail.com',
                'role' => User::ROLE_MANAGER,
                'department_codes' => ['MM', 'CORP'],
                'primary_dept' => 'MM',
            ],
            [
                'name' => 'Nqobile Vakalisa',
                'email' => 'vakalisan@ndz.gov.za',
                'role' => User::ROLE_MANAGER,
                'department_codes' => ['MM', 'CORP'],
                'primary_dept' => 'MM',
            ],
        ];

        $outputRows = [];

        foreach ($staffMembers as $member) {
            $email = strtolower(trim($member['email']));

            // Resolve department IDs
            $deptIds = [];
            foreach ($member['department_codes'] as $code) {
                if (isset($departmentsByCode[$code])) {
                    $deptIds[] = $departmentsByCode[$code]->id;
                }
            }

            $primaryDeptId = null;
            if (!empty($member['primary_dept']) && isset($departmentsByCode[$member['primary_dept']])) {
                $primaryDeptId = $departmentsByCode[$member['primary_dept']]->id;
            } elseif (!empty($deptIds)) {
                $primaryDeptId = $deptIds[0];
            }

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $member['name'],
                    'role' => $member['role'],
                    'department_id' => $primaryDeptId,
                    'password' => $hashedPassword,
                    'email_verified_at' => now(),
                ]
            );

            if (!empty($deptIds)) {
                $user->departments()->sync($deptIds);
            }

            $outputRows[] = [
                $user->name,
                $user->email,
                $user->role,
                implode(', ', $member['department_codes']),
                $defaultPassword,
            ];
        }

        if (isset($this->command)) {
            $this->command->table(
                ['Name', 'Email', 'Role', 'Departments', 'Default Password'],
                $outputRows
            );
            $this->command->info('Municipal staff users have been successfully seeded.');
        }
    }
}
