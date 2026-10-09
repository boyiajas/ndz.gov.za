<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VacancyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_can_list_open_vacancies(): void
    {
        $response = $this->getJson('/api/vacancies?status=open');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'department', 'status', 'closing_date'],
                ],
                'meta' => ['total', 'open_count', 'closed_count'],
            ]);
    }

    public function test_public_can_view_single_vacancy(): void
    {
        $vacancy = Vacancy::first();
        $this->assertNotNull($vacancy);

        $response = $this->getJson("/api/vacancies/{$vacancy->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $vacancy->id);
    }

    public function test_guest_cannot_access_admin_vacancies(): void
    {
        $response = $this->getJson('/api/admin/vacancies');

        $response->assertStatus(401);
    }

    public function test_citizen_cannot_manage_vacancies(): void
    {
        $citizen = User::factory()->create(['role' => User::ROLE_CITIZEN]);

        $response = $this->actingAs($citizen)->getJson('/api/admin/vacancies');
        $response->assertStatus(403);

        $postResponse = $this->actingAs($citizen)->postJson('/api/admin/vacancies', [
            'title' => 'Unauthorized Post',
            'department' => 'Corporate Services',
            'status' => 'open',
        ]);
        $postResponse->assertStatus(403);
    }

    public function test_admin_can_create_and_upload_vacancy_document(): void
    {
        Storage::fake('public');

        $admin = User::where('role', User::ROLE_ADMIN)->first() ?? User::factory()->create(['role' => User::ROLE_ADMIN]);

        $file = UploadedFile::fake()->create('job-spec-analyst.pdf', 500, 'application/pdf');

        $response = $this->actingAs($admin)->postJson('/api/admin/vacancies', [
            'title' => 'Senior Financial Analyst',
            'reference_no' => 'NDZ-TEST-99/2026',
            'department' => 'Budget & Treasury',
            'status' => 'open',
            'closing_date' => now()->addDays(14)->toDateString(),
            'remuneration' => 'Task Grade 15',
            'location' => 'Creighton Main Office',
            'description' => 'Test financial analysis role overview.',
            'requirements' => 'BCom Accounting, 3 years exp.',
            'file' => $file,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Senior Financial Analyst')
            ->assertJsonPath('data.reference_no', 'NDZ-TEST-99/2026');

        $this->assertDatabaseHas('vacancies', [
            'reference_no' => 'NDZ-TEST-99/2026',
            'status' => 'open',
        ]);
    }

    public function test_admin_can_toggle_vacancy_status(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first() ?? User::factory()->create(['role' => User::ROLE_ADMIN]);

        $vacancy = Vacancy::where('status', 'open')->first();
        $this->assertNotNull($vacancy);

        $response = $this->actingAs($admin)->postJson("/api/admin/vacancies/{$vacancy->id}/toggle-status");

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'closed');

        $this->assertDatabaseHas('vacancies', [
            'id' => $vacancy->id,
            'status' => 'closed',
        ]);
    }

    public function test_admin_can_delete_vacancy(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first() ?? User::factory()->create(['role' => User::ROLE_ADMIN]);

        $vacancy = Vacancy::create([
            'title' => 'Temporary Vacancy to Delete',
            'reference_no' => 'NDZ-DEL-01/2026',
            'department' => 'Corporate Services',
            'status' => 'closed',
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->deleteJson("/api/admin/vacancies/{$vacancy->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('vacancies', [
            'id' => $vacancy->id,
        ]);
    }
}
