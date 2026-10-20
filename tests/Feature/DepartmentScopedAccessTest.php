<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DepartmentScopedAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function getDepartmentUser(string $deptCode = 'BTO'): User
    {
        $dept = Department::where('code', $deptCode)->first();
        $user = User::firstOrCreate(
            ['email' => strtolower($deptCode) . '-test@ndz.gov.za'],
            [
                'name' => "{$deptCode} Officer",
                'role' => User::ROLE_EDITOR,
                'department_id' => $dept->id,
                'password' => Hash::make('Password123!'),
            ]
        );
        $user->departments()->sync([$dept->id]);
        return $user;
    }

    protected function getAdminUser(): User
    {
        return User::where('role', User::ROLE_ADMIN)->first() ?? User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    public function test_public_can_fetch_departments(): void
    {
        $response = $this->getJson('/api/departments');
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'name', 'code', 'slug']]]);

        $this->assertGreaterThanOrEqual(6, count($response->json('data')));
    }

    public function test_department_user_only_sees_own_department_categories(): void
    {
        $btoUser = $this->getDepartmentUser('BTO');
        $this->assertNotNull($btoUser);
        $this->assertNotNull($btoUser->department_id);

        $response = $this->actingAs($btoUser)->getJson('/api/admin/document-categories');
        $response->assertStatus(200);

        $categories = $response->json('data');
        $this->assertNotEmpty($categories);

        foreach ($categories as $cat) {
            $this->assertEquals($btoUser->department_id, $cat['department_id'], 'Category department_id must match user department');
        }
    }

    public function test_global_admin_sees_all_categories_and_can_filter_by_department(): void
    {
        $admin = $this->getAdminUser();
        $this->assertNotNull($admin);

        // Without filter: sees all categories
        $responseAll = $this->actingAs($admin)->getJson('/api/admin/document-categories');
        $responseAll->assertStatus(200);
        $allCategories = $responseAll->json('data');

        // With department filter
        $btoDept = Department::where('code', 'BTO')->first();
        $this->assertNotNull($btoDept);

        $responseFiltered = $this->actingAs($admin)->getJson('/api/admin/document-categories?department_id=' . $btoDept->id);
        $responseFiltered->assertStatus(200);
        $filteredCategories = $responseFiltered->json('data');

        $this->assertNotEmpty($filteredCategories);
        $this->assertLessThan(count($allCategories), count($filteredCategories));

        foreach ($filteredCategories as $cat) {
            $this->assertEquals($btoDept->id, $cat['department_id']);
        }
    }

    public function test_department_user_cannot_upload_document_to_another_department_category(): void
    {
        $btoUser = $this->getDepartmentUser('BTO');
        $commDept = Department::where('code', 'COMM')->first();
        $commCategory = DocumentCategory::where('department_id', $commDept->id)->first();
        $this->assertNotNull($commCategory);

        $payload = [
            'document_category_id' => $commCategory->id,
            'title' => 'Illegal Cross-Department File',
            'file_url' => '/documents/test.pdf',
            'status' => 'published',
            'published_at' => now()->toDateString(),
        ];

        $response = $this->actingAs($btoUser)->postJson('/api/admin/documents', $payload);
        $response->assertStatus(403);
    }

    public function test_department_user_can_upload_document_to_own_department_category(): void
    {
        $btoUser = $this->getDepartmentUser('BTO');
        $btoCategory = DocumentCategory::where('department_id', $btoUser->department_id)->first();
        $this->assertNotNull($btoCategory);

        $payload = [
            'document_category_id' => $btoCategory->id,
            'title' => 'BTO Test Upload Report',
            'file_url' => '/documents/bto-report.pdf',
            'status' => 'published',
            'published_at' => now()->toDateString(),
        ];

        $response = $this->actingAs($btoUser)->postJson('/api/admin/documents', $payload);
        $response->assertStatus(201);
        $this->assertEquals($btoUser->department_id, $response->json('data.department_id'));
    }

    public function test_admin_can_assign_department_and_role_to_user(): void
    {
        $admin = $this->getAdminUser();
        $corpDept = Department::where('code', 'CORP')->first();
        $this->assertNotNull($corpDept);

        // Create new user with department
        $response = $this->actingAs($admin)->postJson('/api/admin/users', [
            'name' => 'Thabo Mokoena',
            'email' => 'tmokoena@ndz.gov.za',
            'role' => User::ROLE_EDITOR,
            'department_id' => $corpDept->id,
            'password' => 'Password123!',
        ]);

        $response->assertStatus(201);
        $createdUser = User::where('email', 'tmokoena@ndz.gov.za')->first();
        $this->assertNotNull($createdUser);
        $this->assertEquals(User::ROLE_EDITOR, $createdUser->role);
        $this->assertEquals($corpDept->id, $createdUser->department_id);

        // Admin can update department
        $pwDept = Department::where('code', 'PW')->first();
        $this->assertNotNull($pwDept);

        $updateResponse = $this->actingAs($admin)->putJson('/api/admin/users/' . $createdUser->id, [
            'name' => 'Thabo Mokoena',
            'email' => 'tmokoena@ndz.gov.za',
            'role' => User::ROLE_MANAGER,
            'department_id' => $pwDept->id,
        ]);

        $updateResponse->assertStatus(200);
        $this->assertEquals($pwDept->id, $createdUser->fresh()->department_id);
        $this->assertEquals(User::ROLE_MANAGER, $createdUser->fresh()->role);
    }

    public function test_user_can_belong_to_multiple_departments(): void
    {
        $admin = $this->getAdminUser();
        $btoDept = Department::where('code', 'BTO')->first();
        $pwDept = Department::where('code', 'PW')->first();
        $commDept = Department::where('code', 'COMM')->first();

        // Create user assigned to both BTO and PW
        $response = $this->actingAs($admin)->postJson('/api/admin/users', [
            'name' => 'Multi Dept Officer',
            'email' => 'multidept@ndz.gov.za',
            'role' => User::ROLE_EDITOR,
            'department_ids' => [$btoDept->id, $pwDept->id],
            'password' => 'Password123!',
        ]);

        $response->assertStatus(201);
        $user = User::where('email', 'multidept@ndz.gov.za')->first();
        $this->assertNotNull($user);
        $this->assertCount(2, $user->departments);
        $this->assertTrue($user->hasDepartment($btoDept->id));
        $this->assertTrue($user->hasDepartment($pwDept->id));
        $this->assertFalse($user->hasDepartment($commDept->id));

        // When listing categories, user sees categories belonging to BOTH BTO and PW
        $catResponse = $this->actingAs($user)->getJson('/api/admin/document-categories');
        $catResponse->assertStatus(200);
        $catDeptIds = collect($catResponse->json('data'))->pluck('department_id')->unique()->all();

        $this->assertContains($btoDept->id, $catDeptIds);
        $this->assertContains($pwDept->id, $catDeptIds);
        $this->assertNotContains($commDept->id, $catDeptIds);

        // Can upload to BTO
        $btoCategory = DocumentCategory::where('department_id', $btoDept->id)->first();
        $uploadBto = $this->actingAs($user)->postJson('/api/admin/documents', [
            'document_category_id' => $btoCategory->id,
            'title' => 'Multi-dept BTO report',
            'file_url' => '/documents/bto.pdf',
            'status' => 'published',
        ]);
        $uploadBto->assertStatus(201);

        // Can upload to PW
        $pwCategory = DocumentCategory::where('department_id', $pwDept->id)->first();
        $uploadPw = $this->actingAs($user)->postJson('/api/admin/documents', [
            'document_category_id' => $pwCategory->id,
            'title' => 'Multi-dept PW report',
            'file_url' => '/documents/pw.pdf',
            'status' => 'published',
        ]);
        $uploadPw->assertStatus(201);

        // Cannot upload to COMM
        $commCategory = DocumentCategory::where('department_id', $commDept->id)->first();
        $uploadComm = $this->actingAs($user)->postJson('/api/admin/documents', [
            'document_category_id' => $commCategory->id,
            'title' => 'Illegal Comm report',
            'file_url' => '/documents/comm.pdf',
            'status' => 'published',
        ]);
        $uploadComm->assertStatus(403);
    }

    public function test_admin_can_crud_departments(): void
    {
        $admin = $this->getAdminUser();

        // 1. Index departments with summary
        $indexResponse = $this->actingAs($admin)->getJson('/api/admin/departments');
        $indexResponse->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'summary' => ['total_departments', 'active_departments', 'total_categories_mapped', 'total_documents_mapped'],
            ]);

        // 2. Create new department
        $createResponse = $this->actingAs($admin)->postJson('/api/admin/departments', [
            'name' => 'Office of the Executive Mayor',
            'code' => 'MAYOR',
            'email' => 'mayor@ndz.gov.za',
            'head_title' => 'Executive Mayor',
            'description' => 'Political leadership and executive mayoral committee governance.',
            'sort_order' => 10,
            'is_active' => true,
        ]);

        $createResponse->assertStatus(201)
            ->assertJsonPath('data.code', 'MAYOR');

        $deptId = $createResponse->json('data.id');

        // 3. Update department
        $updateResponse = $this->actingAs($admin)->putJson('/api/admin/departments/' . $deptId, [
            'name' => 'Office of the Executive Mayor (Updated)',
            'code' => 'MAYOR',
            'email' => 'newmayor@ndz.gov.za',
            'head_title' => 'Executive Mayor & EXCO',
            'is_active' => true,
        ]);

        $updateResponse->assertStatus(200)
            ->assertJsonPath('data.name', 'Office of the Executive Mayor (Updated)');

        // 4. Delete department
        $deleteResponse = $this->actingAs($admin)->deleteJson('/api/admin/departments/' . $deptId);
        $deleteResponse->assertStatus(200);
        $this->assertDatabaseMissing('departments', ['id' => $deptId]);
    }

    public function test_admin_cannot_delete_department_with_linked_categories(): void
    {
        $admin = $this->getAdminUser();
        $btoDept = Department::where('code', 'BTO')->first();

        $response = $this->actingAs($admin)->deleteJson('/api/admin/departments/' . $btoDept->id);
        $response->assertStatus(422)
            ->assertJsonStructure(['message']);
    }

    public function test_admin_can_trigger_seed_defaults(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->postJson('/api/admin/departments/seed-defaults');
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data']);
    }
}
