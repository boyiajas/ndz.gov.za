<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->getJson('/api/profile');
        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_view_their_profile(): void
    {
        $dept = Department::create([
            'name' => 'Corporate Services',
            'code' => 'CS',
            'slug' => 'corporate-services',
        ]);

        $user = User::factory()->create([
            'name' => 'Mfuneko Spengane',
            'email' => 'spenganem@ndz.gov.za',
            'phone' => '+27 39 833 1038',
            'job_title' => 'Chief Technology Officer',
            'role' => User::ROLE_ADMIN,
            'department_id' => $dept->id,
        ]);
        $user->departments()->attach($dept->id);

        $response = $this->actingAs($user)->getJson('/api/profile');

        $response->assertOk()
            ->assertJsonPath('data.name', 'Mfuneko Spengane')
            ->assertJsonPath('data.email', 'spenganem@ndz.gov.za')
            ->assertJsonPath('data.phone', '+27 39 833 1038')
            ->assertJsonPath('data.job_title', 'Chief Technology Officer')
            ->assertJsonPath('data.role', User::ROLE_ADMIN)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'role',
                    'job_title',
                    'department_id',
                    'department',
                    'departments',
                    'permissions',
                    'recent_activity',
                    'stats',
                ],
            ]);
    }

    public function test_authenticated_user_can_update_their_personal_info(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@ndz.gov.za',
            'phone' => '0398331000',
            'job_title' => 'Staff Member',
        ]);

        $response = $this->actingAs($user)->putJson('/api/profile', [
            'name' => 'Updated Name',
            'email' => 'updated@ndz.gov.za',
            'phone' => '+27 82 123 4567',
            'job_title' => 'Senior Officer',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Profile updated successfully.')
            ->assertJsonPath('data.name', 'Updated Name')
            ->assertJsonPath('data.email', 'updated@ndz.gov.za')
            ->assertJsonPath('data.phone', '+27 82 123 4567')
            ->assertJsonPath('data.job_title', 'Senior Officer');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@ndz.gov.za',
            'phone' => '+27 82 123 4567',
            'job_title' => 'Senior Officer',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'module' => 'Users',
            'action' => 'update',
        ]);
    }

    public function test_user_cannot_update_profile_with_existing_email_from_another_user(): void
    {
        User::factory()->create(['email' => 'existing@ndz.gov.za']);

        $user = User::factory()->create(['email' => 'myemail@ndz.gov.za']);

        $response = $this->actingAs($user)->putJson('/api/profile', [
            'name' => $user->name,
            'email' => 'existing@ndz.gov.za',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_user_can_update_their_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('CurrentSecret123!'),
        ]);

        $response = $this->actingAs($user)->putJson('/api/profile/password', [
            'current_password' => 'CurrentSecret123!',
            'password' => 'NewSecretPassword456!',
            'password_confirmation' => 'NewSecretPassword456!',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Password updated successfully.');

        $this->assertTrue(Hash::check('NewSecretPassword456!', $user->fresh()->password));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'module' => 'Auth',
            'action' => 'update',
        ]);
    }

    public function test_user_cannot_update_password_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('CorrectSecret123!'),
        ]);

        $response = $this->actingAs($user)->putJson('/api/profile/password', [
            'current_password' => 'WrongPassword999!',
            'password' => 'NewSecretPassword456!',
            'password_confirmation' => 'NewSecretPassword456!',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['current_password']);
    }

    public function test_admin_can_view_other_user_profile(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $targetUser = User::factory()->create([
            'name' => 'Target User',
            'email' => 'target@ndz.gov.za',
            'role' => User::ROLE_EDITOR,
        ]);

        $response = $this->actingAs($admin)->getJson("/api/profile/{$targetUser->id}");

        $response->assertOk()
            ->assertJsonPath('data.name', 'Target User')
            ->assertJsonPath('data.email', 'target@ndz.gov.za')
            ->assertJsonPath('data.role', User::ROLE_EDITOR);
    }

    public function test_regular_citizen_cannot_view_other_user_profile(): void
    {
        $citizen = User::factory()->create(['role' => User::ROLE_CITIZEN]);
        $targetUser = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $response = $this->actingAs($citizen)->getJson("/api/profile/{$targetUser->id}");

        $response->assertForbidden();
    }
}
