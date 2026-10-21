<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\ProcurementNotice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditTrailTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_audit_logs(): void
    {
        $response = $this->getJson('/api/admin/audit-logs');
        $response->assertUnauthorized();
    }

    public function test_citizen_cannot_access_audit_logs(): void
    {
        $citizen = User::factory()->create([
            'role' => User::ROLE_CITIZEN,
        ]);

        $response = $this->actingAs($citizen)->getJson('/api/admin/audit-logs');
        $response->assertForbidden();
    }

    public function test_admin_and_manager_can_access_audit_logs_and_filters(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $manager = User::factory()->create([
            'role' => User::ROLE_MANAGER,
        ]);

        AuditLog::create([
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_email' => $admin->email,
            'action' => 'login',
            'module' => 'Auth',
            'description' => 'User logged into portal',
        ]);

        // Test admin access
        $adminResp = $this->actingAs($admin)->getJson('/api/admin/audit-logs');
        $adminResp->assertOk()
            ->assertJsonStructure(['data', 'current_page', 'total']);

        $filtersResp = $this->actingAs($admin)->getJson('/api/admin/audit-logs/filters');
        $filtersResp->assertOk()
            ->assertJsonStructure(['users', 'departments', 'modules', 'actions', 'stats']);

        // Test manager access
        $managerResp = $this->actingAs($manager)->getJson('/api/admin/audit-logs');
        $managerResp->assertOk();
    }

    public function test_filtering_audit_logs_by_user_department_and_action(): void
    {
        $dept1 = Department::create(['name' => 'Corporate Support Services', 'code' => 'CSS', 'slug' => 'corporate-support-services']);
        $dept2 = Department::create(['name' => 'Budget and Treasury', 'code' => 'BTO', 'slug' => 'budget-and-treasury']);

        $user1 = User::factory()->create([
            'name' => 'Alice Admin',
            'role' => User::ROLE_ADMIN,
            'department_id' => $dept1->id,
        ]);

        $user2 = User::factory()->create([
            'name' => 'Bob Manager',
            'role' => User::ROLE_MANAGER,
            'department_id' => $dept2->id,
        ]);

        $log1 = AuditLog::create([
            'user_id' => $user1->id,
            'user_name' => $user1->name,
            'user_email' => $user1->email,
            'department_id' => $dept1->id,
            'department_name' => $dept1->name,
            'action' => 'upload',
            'module' => 'Documents',
            'description' => 'Uploaded budget report',
            'created_at' => now()->subDay(),
        ]);

        $log2 = AuditLog::create([
            'user_id' => $user2->id,
            'user_name' => $user2->name,
            'user_email' => $user2->email,
            'department_id' => $dept2->id,
            'department_name' => $dept2->name,
            'action' => 'create',
            'module' => 'Procurement',
            'description' => 'Created tender NDZ-001',
            'created_at' => now(),
        ]);

        // Filter by user
        $respUser = $this->actingAs($user1)->getJson('/api/admin/audit-logs?user_id=' . $user1->id);
        $respUser->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $log1->id);

        // Filter by department
        $respDept = $this->actingAs($user1)->getJson('/api/admin/audit-logs?department_id=' . $dept2->id);
        $respDept->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $log2->id);

        // Filter by action
        $respAction = $this->actingAs($user1)->getJson('/api/admin/audit-logs?action=upload');
        $respAction->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $log1->id);

        // Filter by module
        $respModule = $this->actingAs($user1)->getJson('/api/admin/audit-logs?module=Procurement');
        $respModule->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $log2->id);
    }

    public function test_audit_logs_can_be_exported_to_csv(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        AuditLog::create([
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_email' => $admin->email,
            'action' => 'login',
            'module' => 'Auth',
            'description' => 'User logged into portal',
            'ip_address' => '127.0.0.1',
        ]);

        $response = $this->actingAs($admin)->get('/api/admin/audit-logs/export');
        $response->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();
        $this->assertStringContainsString('User logged into portal', $content);
        $this->assertStringContainsString('Auth', $content);
        $this->assertStringContainsString('LOGIN', $content);
    }

    public function test_actions_generate_audit_trail_entries(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'password' => bcrypt('password123'),
        ]);

        // Test login logs audit
        $this->postJson('/api/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'login',
            'module' => 'Auth',
        ]);

        // Test procurement notice creation logs audit
        $this->actingAs($admin)->postJson('/api/admin/procurement', [
            'type' => 'tender',
            'status' => 'open',
            'financial_year' => '2026-2027',
            'category' => 'Open Tenders',
            'title' => 'Bridge Renovation Tender',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'create',
            'module' => 'Procurement',
        ]);
    }
}
