<?php

namespace Tests\Feature;

use App\Models\ProcurementNotice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcurementTenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_fetch_procurement_years(): void
    {
        ProcurementNotice::create([
            'title' => 'Tender 1',
            'financial_year' => '2025-2026',
            'type' => 'tender',
            'status' => 'open',
            'reference_no' => 'NDZ-TDR-001',
        ]);

        ProcurementNotice::create([
            'title' => 'Tender 2',
            'financial_year' => '2024-2025',
            'type' => 'tender',
            'status' => 'closed',
            'reference_no' => 'NDZ-TDR-002',
        ]);

        $response = $this->getJson('/api/procurement/years');
        $response->assertOk()
            ->assertJson([
                'data' => ['2025-2026', '2024-2025'],
            ]);
    }

    public function test_public_can_filter_tenders_by_year_and_category(): void
    {
        ProcurementNotice::create([
            'title' => 'Open Tender 2025',
            'financial_year' => '2025-2026',
            'category' => 'Open Tenders',
            'type' => 'tender',
            'status' => 'open',
            'reference_no' => 'NDZ-TDR-001',
        ]);

        ProcurementNotice::create([
            'title' => 'Bid Pack 2025',
            'financial_year' => '2025-2026',
            'category' => 'Bid Documents',
            'type' => 'tender',
            'status' => 'open',
            'reference_no' => 'NDZ-TDR-002',
        ]);

        $response = $this->getJson('/api/procurement?financial_year=2025-2026&category=Open Tenders');
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Open Tender 2025');
    }

    public function test_public_can_filter_quotations_by_year_and_type(): void
    {
        ProcurementNotice::create([
            'title' => 'Supply and Delivery of Brush Cutters',
            'financial_year' => '2026-2027',
            'category' => 'Quotations',
            'type' => 'quote',
            'status' => 'open',
            'reference_no' => 'NDZ-RFQ-2026-001',
        ]);

        ProcurementNotice::create([
            'title' => 'Open Tender 2026',
            'financial_year' => '2026-2027',
            'category' => 'Open Tenders',
            'type' => 'tender',
            'status' => 'open',
            'reference_no' => 'NDZ-TDR-2026-001',
        ]);

        $response = $this->getJson('/api/procurement?financial_year=2026-2027&type=quote');
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Supply and Delivery of Brush Cutters');
    }

    public function test_public_can_filter_closed_tenders_and_quotes(): void
    {
        ProcurementNotice::create([
            'title' => 'Closed Tender 2024',
            'financial_year' => '2024-2025',
            'category' => 'Open Tenders',
            'type' => 'tender',
            'status' => 'closed',
            'reference_no' => 'NDZ-TDR-CLOSED-01',
        ]);

        ProcurementNotice::create([
            'title' => 'Open Tender 2024',
            'financial_year' => '2024-2025',
            'category' => 'Open Tenders',
            'type' => 'tender',
            'status' => 'open',
            'reference_no' => 'NDZ-TDR-OPEN-01',
        ]);

        $response = $this->getJson('/api/procurement?financial_year=2024-2025&status=closed&type=tender');
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Closed Tender 2024')
            ->assertJsonPath('data.0.status', 'closed');
    }

    public function test_public_can_fetch_bid_and_quote_documents(): void
    {
        ProcurementNotice::create([
            'title' => 'BOQ FOR NOMGIDI COMMUNITY HALL',
            'financial_year' => 'Current',
            'category' => 'Bid Documents',
            'type' => 'tender',
            'status' => 'open',
            'reference_no' => 'NDZ-BID-Current-001',
            'document_url' => '/storage/uploads/bid_documents/boq.xlsx',
        ]);

        ProcurementNotice::create([
            'title' => 'SERVICE PROVIDER FOR REPAIRS',
            'financial_year' => 'Current',
            'category' => 'Quote Documents',
            'type' => 'quote',
            'status' => 'open',
            'reference_no' => 'NDZ-RFQ-DOC-Current-001',
            'document_url' => '/storage/uploads/quote_documents/repairs.pdf',
        ]);

        $bids = $this->getJson('/api/procurement?category=Bid Documents');
        $bids->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'BOQ FOR NOMGIDI COMMUNITY HALL');

        $quotes = $this->getJson('/api/procurement?category=Quote Documents');
        $quotes->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'SERVICE PROVIDER FOR REPAIRS');
    }

    public function test_public_can_fetch_intention_to_award_documents(): void
    {
        ProcurementNotice::create([
            'title' => 'INTENTION TO AWARD - SUPPLY AND DELIVERY OF ROAD SIGNS',
            'financial_year' => '2020-2021',
            'category' => 'Intention To Award',
            'type' => 'tender',
            'status' => 'open',
            'reference_no' => 'NDZ-AWARD-2020-001',
            'document_url' => '/storage/uploads/intention_to_award/road_signs.pdf',
            'download_count' => 14,
        ]);

        $response = $this->getJson('/api/procurement?category=Intention To Award');
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'INTENTION TO AWARD - SUPPLY AND DELIVERY OF ROAD SIGNS')
            ->assertJsonPath('data.0.category', 'Intention To Award')
            ->assertJsonPath('data.0.download_count', 14);
    }

    public function test_public_can_fetch_briefing_link_documents(): void
    {
        ProcurementNotice::create([
            'title' => 'Briefing link for GEOHYDROLOGICAL SPECIALIST',
            'financial_year' => 'Current',
            'category' => 'Briefing Link',
            'type' => 'tender',
            'status' => 'open',
            'reference_no' => 'NDZ-BL-001',
            'document_url' => '/storage/uploads/briefing_links/briefing_link_001.pdf',
            'download_count' => 75,
        ]);

        $response = $this->getJson('/api/procurement?category=Briefing Link');
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Briefing link for GEOHYDROLOGICAL SPECIALIST')
            ->assertJsonPath('data.0.category', 'Briefing Link')
            ->assertJsonPath('data.0.download_count', 75);
    }

    public function test_public_can_fetch_erratum_and_addendum_documents(): void
    {
        ProcurementNotice::create([
            'title' => 'ADDENDUM FOR SUPPLY AND DELIVERY OF BRUSH CUTTER',
            'financial_year' => 'Current',
            'category' => 'Erratum And Addendum',
            'type' => 'tender',
            'status' => 'open',
            'reference_no' => 'NDZ-EA-001',
            'document_url' => '/storage/uploads/erratum_and_addendum/erratum_addendum_001.pdf',
            'download_count' => 8,
        ]);

        $response = $this->getJson('/api/procurement?category=Erratum And Addendum');
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'ADDENDUM FOR SUPPLY AND DELIVERY OF BRUSH CUTTER')
            ->assertJsonPath('data.0.category', 'Erratum And Addendum')
            ->assertJsonPath('data.0.download_count', 8);
    }

    public function test_downloading_tender_increments_download_count(): void
    {
        $notice = ProcurementNotice::create([
            'title' => 'Downloadable Notice',
            'financial_year' => '2025-2026',
            'category' => 'Open Tenders',
            'type' => 'tender',
            'status' => 'open',
            'reference_no' => 'NDZ-TDR-DL',
            'document_url' => '/storage/uploads/tenders/sample.pdf',
            'download_count' => 10,
        ]);

        $response = $this->get('/api/procurement/' . $notice->id . '/download');
        $response->assertRedirect('/storage/uploads/tenders/sample.pdf');

        $this->assertEquals(11, $notice->fresh()->download_count);
    }

    public function test_admin_can_create_and_manage_tender(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $payload = [
            'title' => 'Construction of Creighton Bridge',
            'financial_year' => '2025-2026',
            'category' => 'Open Tenders',
            'type' => 'tender',
            'status' => 'open',
            'reference_no' => 'NDZ-PWBS-2025-01',
            'document_url' => '/storage/uploads/tenders/bridge.pdf',
            'download_count' => 5,
        ];

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/admin/procurement', $payload);
        $response->assertCreated()
            ->assertJsonPath('data.title', 'Construction of Creighton Bridge')
            ->assertJsonPath('data.category', 'Open Tenders');

        $noticeId = $response->json('data.id');

        // Update
        $updateResponse = $this->actingAs($admin, 'sanctum')->putJson("/api/admin/procurement/{$noticeId}", [
            'title' => 'Construction of Creighton Bridge - Phase 2',
            'financial_year' => '2025-2026',
            'category' => 'Open Tenders',
            'type' => 'tender',
            'status' => 'open',
        ]);
        $updateResponse->assertOk()
            ->assertJsonPath('data.title', 'Construction of Creighton Bridge - Phase 2');

        // Delete
        $deleteResponse = $this->actingAs($admin, 'sanctum')->deleteJson("/api/admin/procurement/{$noticeId}");
        $deleteResponse->assertOk();

        $this->assertDatabaseMissing('procurement_notices', ['id' => $noticeId]);
    }

    public function test_admin_can_create_and_manage_quotation(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $payload = [
            'title' => 'Service Provider to Conduct Training on Advanced First Aid Responder',
            'financial_year' => '2026-2027',
            'category' => 'Quotations',
            'type' => 'quote',
            'status' => 'open',
            'reference_no' => 'NDZ-RFQ-2026-001',
            'document_url' => '/storage/uploads/quotes/quote_001.pdf',
            'download_count' => 30,
        ];

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/admin/procurement', $payload);
        $response->assertCreated()
            ->assertJsonPath('data.title', 'Service Provider to Conduct Training on Advanced First Aid Responder')
            ->assertJsonPath('data.type', 'quote')
            ->assertJsonPath('data.category', 'Quotations');

        $quoteId = $response->json('data.id');

        $updateResponse = $this->actingAs($admin, 'sanctum')->putJson("/api/admin/procurement/{$quoteId}", [
            'title' => 'Service Provider to Conduct Training on Advanced First Aid Responder - Updated',
            'financial_year' => '2026-2027',
            'category' => 'Quotations',
            'type' => 'quote',
            'status' => 'open',
        ]);
        $updateResponse->assertOk()
            ->assertJsonPath('data.title', 'Service Provider to Conduct Training on Advanced First Aid Responder - Updated');

        $deleteResponse = $this->actingAs($admin, 'sanctum')->deleteJson("/api/admin/procurement/{$quoteId}");
        $deleteResponse->assertOk();

        $this->assertDatabaseMissing('procurement_notices', ['id' => $quoteId]);
    }

    public function test_citizen_cannot_manage_procurement(): void
    {
        $citizen = User::factory()->create([
            'role' => User::ROLE_CITIZEN,
        ]);

        $response = $this->actingAs($citizen, 'sanctum')->postJson('/api/admin/procurement', [
            'title' => 'Unauthorized Notice',
            'financial_year' => '2025-2026',
            'type' => 'tender',
            'status' => 'open',
        ]);

        $response->assertForbidden();
    }
}
