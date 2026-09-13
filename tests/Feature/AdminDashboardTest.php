<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\GalleryItem;
use App\Models\ProcurementNotice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_cannot_access_admin_dashboard_stats(): void
    {
        $response = $this->getJson('/api/admin/dashboard-stats');
        $response->assertStatus(401);
    }

    public function test_citizen_cannot_access_admin_endpoints(): void
    {
        $citizen = User::where('role', User::ROLE_CITIZEN)->first();

        $response = $this->actingAs($citizen)->getJson('/api/admin/news');
        $response->assertStatus(403);

        $response = $this->actingAs($citizen)->getJson('/api/admin/users');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard_stats(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->getJson('/api/admin/dashboard-stats');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'stats' => [
                    'documents_count',
                    'news_count',
                    'gallery_count',
                    'tenders_count',
                    'quotes_count',
                    'users_count',
                ],
                'recent_articles',
                'recent_documents',
                'recent_notices',
                'user',
            ]);
    }

    public function test_admin_can_crud_news_articles(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        // Create
        $response = $this->actingAs($admin)->postJson('/api/admin/news', [
            'title' => 'Test Municipal Story',
            'category' => 'News',
            'excerpt' => 'This is a test summary for the story.',
            'content' => '<p>Full content of the story.</p>',
            'image_url' => '/test.jpg',
            'read_time' => '2 min',
            'is_featured' => true,
            'is_published' => true,
        ]);
        $response->assertStatus(201);
        $articleId = $response->json('data.id');

        // Update
        $response = $this->actingAs($admin)->putJson("/api/admin/news/{$articleId}", [
            'title' => 'Updated Municipal Story',
            'category' => 'Press Release',
            'excerpt' => 'Updated summary.',
            'content' => '<p>Updated content.</p>',
            'image_url' => '/updated.jpg',
            'read_time' => '3 min',
            'is_featured' => false,
            'is_published' => true,
        ]);
        $response->assertStatus(200)->assertJsonPath('data.title', 'Updated Municipal Story');

        // Delete
        $response = $this->actingAs($admin)->deleteJson("/api/admin/news/{$articleId}");
        $response->assertStatus(200);
    }

    public function test_admin_can_crud_gallery_items(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->postJson('/api/admin/gallery', [
            'title' => 'New Scenic Spot',
            'category' => 'Tourism',
            'image_url' => '/img/test-scenic.jpg',
            'description' => 'Beautiful spot',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $response->assertStatus(201);
        $itemId = $response->json('data.id');

        $response = $this->actingAs($admin)->deleteJson("/api/admin/gallery/{$itemId}");
        $response->assertStatus(200);
    }

    public function test_admin_can_manage_users_and_view_roles(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        // Roles
        $response = $this->actingAs($admin)->getJson('/api/admin/roles');
        $response->assertStatus(200)->assertJsonStructure(['data']);

        // Create User
        $response = $this->actingAs($admin)->postJson('/api/admin/users', [
            'name' => 'New Official',
            'email' => 'official@ndz.gov.za',
            'role' => User::ROLE_MANAGER,
            'password' => 'SecurePass123!',
        ]);
        $response->assertStatus(201);
        $newUserId = $response->json('data.id');

        // Update User
        $response = $this->actingAs($admin)->putJson("/api/admin/users/{$newUserId}", [
            'name' => 'New Official Updated',
            'email' => 'official@ndz.gov.za',
            'role' => User::ROLE_EDITOR,
        ]);
        $response->assertStatus(200)->assertJsonPath('data.role', User::ROLE_EDITOR);

        // Delete User
        $response = $this->actingAs($admin)->deleteJson("/api/admin/users/{$newUserId}");
        $response->assertStatus(200);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->deleteJson("/api/admin/users/{$admin->id}");
        $response->assertStatus(422);
    }

    public function test_admin_can_update_settings(): void
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $response = $this->actingAs($admin)->postJson('/api/admin/settings', [
            'settings' => [
                'contact_email' => 'custom@ndz.gov.za',
            ],
        ]);
        $response->assertStatus(200);
    }

    public function test_file_upload_endpoint(): void
    {
        Storage::fake('public');
        $admin = User::where('role', User::ROLE_ADMIN)->first();

        $file = UploadedFile::fake()->create('test-spec.pdf', 100, 'application/pdf');

        $response = $this->actingAs($admin)->postJson('/api/admin/upload', [
            'file' => $file,
            'folder' => 'news',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['url', 'filename', 'size']);
    }

    public function test_public_endpoints_return_data(): void
    {
        $this->getJson('/api/news')->assertStatus(200)->assertJsonStructure(['data']);
        $this->getJson('/api/gallery')->assertStatus(200)->assertJsonStructure(['data']);
        $this->getJson('/api/procurement')->assertStatus(200)->assertJsonStructure(['data']);
        $this->getJson('/api/settings')->assertStatus(200)->assertJsonStructure(['data']);
    }
}
