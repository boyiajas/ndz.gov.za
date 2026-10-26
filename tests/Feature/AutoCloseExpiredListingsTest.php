<?php

namespace Tests\Feature;

use App\Jobs\CloseExpiredListingsJob;
use App\Models\AuditLog;
use App\Models\ProcurementNotice;
use App\Models\User;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AutoCloseExpiredListingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_closes_expired_tenders_and_quotes(): void
    {
        // 1. Expired tender (closing date in the past)
        $expiredTender = ProcurementNotice::create([
            'title' => 'Expired Road Maintenance Tender',
            'financial_year' => '2025-2026',
            'type' => 'tender',
            'category' => 'Open Tenders',
            'status' => 'open',
            'reference_no' => 'NDZ-TDR-EXP-01',
            'closing_date' => Carbon::now()->subDays(2),
        ]);

        // 2. Active tender (closing date in the future)
        $activeTender = ProcurementNotice::create([
            'title' => 'Active Water Supply Tender',
            'financial_year' => '2025-2026',
            'type' => 'tender',
            'category' => 'Open Tenders',
            'status' => 'open',
            'reference_no' => 'NDZ-TDR-ACT-01',
            'closing_date' => Carbon::now()->addDays(5),
        ]);

        // 3. Expired quotation (closing date in the past)
        $expiredQuote = ProcurementNotice::create([
            'title' => 'Expired Stationery Quotation',
            'financial_year' => '2025-2026',
            'type' => 'quote',
            'category' => 'Quotations',
            'status' => 'open',
            'reference_no' => 'NDZ-RFQ-EXP-01',
            'closing_date' => Carbon::now()->subHours(5),
        ]);

        // 4. Quotation with no closing date
        $undatedQuote = ProcurementNotice::create([
            'title' => 'Undated Quotation Notice',
            'financial_year' => '2025-2026',
            'type' => 'quote',
            'category' => 'Quotations',
            'status' => 'open',
            'reference_no' => 'NDZ-RFQ-NODATE-01',
            'closing_date' => null,
        ]);

        // 5. Expired vacancy
        $expiredVacancy = Vacancy::create([
            'title' => 'Expired Chief Financial Officer',
            'reference_no' => 'NDZ-VAC-EXP-01',
            'department' => 'Budget and Treasury Office',
            'status' => 'open',
            'closing_date' => Carbon::now()->subDay(),
            'is_active' => true,
        ]);

        // 6. Active vacancy
        $activeVacancy = Vacancy::create([
            'title' => 'Active Town Planner',
            'reference_no' => 'NDZ-VAC-ACT-01',
            'department' => 'Public Works and Basic Services',
            'status' => 'open',
            'closing_date' => Carbon::now()->addDays(10),
            'is_active' => true,
        ]);

        // Execute job
        $job = new CloseExpiredListingsJob();
        $result = $job->handle();

        $this->assertEquals(1, $result['tenders_closed']);
        $this->assertEquals(1, $result['quotes_closed']);
        $this->assertEquals(1, $result['vacancies_closed']);
        $this->assertEquals(3, $result['total_closed']);

        // Check database states
        $this->assertEquals('closed', $expiredTender->fresh()->status);
        $this->assertEquals('open', $activeTender->fresh()->status);
        $this->assertEquals('closed', $expiredQuote->fresh()->status);
        $this->assertEquals('open', $undatedQuote->fresh()->status);
        $this->assertEquals('closed', $expiredVacancy->fresh()->status);
        $this->assertEquals('open', $activeVacancy->fresh()->status);

        // Check Audit Logs
        $auditLogs = AuditLog::where('action', 'auto_close')->get();
        $this->assertCount(3, $auditLogs);
    }

    public function test_artisan_command_runs_successfully(): void
    {
        ProcurementNotice::create([
            'title' => 'Expired Bridge Construction Tender',
            'financial_year' => '2025-2026',
            'type' => 'tender',
            'category' => 'Open Tenders',
            'status' => 'open',
            'reference_no' => 'NDZ-TDR-CMD-01',
            'closing_date' => Carbon::now()->subDays(1),
        ]);

        $this->artisan('listings:close-expired')
            ->expectsOutputToContain('Total listings auto-closed: 1')
            ->assertSuccessful();

        $this->assertDatabaseHas('procurement_notices', [
            'reference_no' => 'NDZ-TDR-CMD-01',
            'status' => 'closed',
        ]);
    }

    public function test_artisan_command_with_queue_and_work_flags(): void
    {
        ProcurementNotice::create([
            'title' => 'Expired Generator RFQ',
            'financial_year' => '2025-2026',
            'type' => 'quote',
            'category' => 'Quotations',
            'status' => 'open',
            'reference_no' => 'NDZ-RFQ-QUEUE-01',
            'closing_date' => Carbon::now()->subHour(),
        ]);

        $this->artisan('listings:close-expired', ['--queue' => true, '--work' => true])
            ->expectsOutputToContain('CloseExpiredListingsJob dispatched to queue successfully.')
            ->assertSuccessful();

        $this->assertDatabaseHas('procurement_notices', [
            'reference_no' => 'NDZ-RFQ-QUEUE-01',
            'status' => 'closed',
        ]);
    }

    public function test_admin_can_trigger_close_expired_via_api(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        ProcurementNotice::create([
            'title' => 'Expired Legal Services Tender',
            'financial_year' => '2025-2026',
            'type' => 'tender',
            'category' => 'Open Tenders',
            'status' => 'open',
            'reference_no' => 'NDZ-TDR-API-01',
            'closing_date' => Carbon::now()->subDays(3),
        ]);

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/admin/procurement/close-expired');

        $response->assertOk()
            ->assertJsonPath('data.tenders_closed', 1)
            ->assertJsonPath('data.total_closed', 1);

        $this->assertDatabaseHas('procurement_notices', [
            'reference_no' => 'NDZ-TDR-API-01',
            'status' => 'closed',
        ]);
    }

    public function test_citizen_cannot_trigger_close_expired_api(): void
    {
        $citizen = User::factory()->create(['role' => User::ROLE_CITIZEN]);

        $response = $this->actingAs($citizen, 'sanctum')->postJson('/api/admin/procurement/close-expired');

        $response->assertForbidden();
    }
}
