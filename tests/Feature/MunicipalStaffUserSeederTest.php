<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\MunicipalStaffUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MunicipalStaffUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_all_required_municipal_staff(): void
    {
        $this->seed(DepartmentSeeder::class);
        $this->seed(MunicipalStaffUserSeeder::class);

        $expectedStaff = [
            'spenganem@ndz.gov.za' => [
                'name' => 'Mfuneko Spengane',
                'role' => User::ROLE_ADMIN,
                'dept_codes' => ['CORP'],
            ],
            'nxelem@ndz.gov.za' => [
                'name' => 'Mthokozisi Nxele',
                'role' => User::ROLE_ADMIN,
                'dept_codes' => ['CORP', 'MM'],
            ],
            'mshengus@ndz.gov.za' => [
                'name' => 'Siyabonga Mshengu',
                'role' => User::ROLE_MANAGER,
                'dept_codes' => ['CORP', 'PW'],
            ],
            'sokhelab@ndz.gov.za' => [
                'name' => 'Buhlebakhe Sokhela',
                'role' => User::ROLE_MANAGER,
                'dept_codes' => ['CORP', 'COMM'],
            ],
            'communications@ndz.gov.za' => [
                'name' => 'Skhumbuzo Sethuntsa',
                'role' => User::ROLE_MANAGER,
                'dept_codes' => ['MM', 'CORP'],
            ],
            'sikhumbuzosethuntsa@gmail.com' => [
                'name' => 'Skhumbuzo Sethuntsa',
                'role' => User::ROLE_MANAGER,
                'dept_codes' => ['MM', 'CORP'],
            ],
            'vakalisan@ndz.gov.za' => [
                'name' => 'Nqobile Vakalisa',
                'role' => User::ROLE_MANAGER,
                'dept_codes' => ['MM', 'CORP'],
            ],
        ];

        foreach ($expectedStaff as $email => $data) {
            $user = User::where('email', $email)->first();
            $this->assertNotNull($user, "User with email {$email} was not found.");
            $this->assertSame($data['name'], $user->name);
            $this->assertSame($data['role'], $user->role);
            $this->assertTrue(Hash::check('Password123!', $user->password), "Password check failed for {$email}");

            $assignedDeptCodes = $user->departments()->pluck('code')->all();
            foreach ($data['dept_codes'] as $expectedCode) {
                $this->assertContains($expectedCode, $assignedDeptCodes, "User {$email} missing department {$expectedCode}");
            }
        }
    }

    public function test_staff_can_login_with_case_insensitive_email(): void
    {
        $this->seed(DepartmentSeeder::class);
        $this->seed(MunicipalStaffUserSeeder::class);

        // Test login with mixed/upper casing
        $response = $this->postJson('/api/login', [
            'email' => 'Spenganem@ndz.gov.za',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email', 'role', 'departments'],
                'token',
            ]);

        $this->assertSame('spenganem@ndz.gov.za', $response->json('user.email'));
    }
}
