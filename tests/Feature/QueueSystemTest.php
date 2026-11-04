<?php

namespace Tests\Feature;

use App\Models\CallHistory;
use App\Models\Counter;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class QueueSystemTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Clean ticket tables for isolated testing
        Schema::disableForeignKeyConstraints();
        Ticket::truncate();
        CallHistory::truncate();
        Schema::enableForeignKeyConstraints();

        // Seed database
        $this->artisan('db:seed');
    }

    public function test_kiosk_page_loads_successfully(): void
    {
        $response = $this->get('/kiosk');
        $response->assertStatus(200);
        $response->assertSee('Pelayanan Perpustakaan');
        $response->assertSee('Pelayanan Konsultasi Statistik');
        $response->assertSee('Pelayanan Rekomendasi Kegiatan Statistik (Romantik)');
    }

    public function test_ticket_generation_is_independent_and_sequential_per_service(): void
    {
        $serviceA = Service::where('code', 'A')->first();
        $serviceB = Service::where('code', 'B')->first();
        $serviceC = Service::where('code', 'C')->first();

        // 1. Take Service A
        $resA1 = $this->postJson('/kiosk/take', ['service_id' => $serviceA->id]);
        $resA1->assertStatus(200)->assertJsonPath('ticket.ticket_number', 'A-001');

        // 2. Take Service A again
        $resA2 = $this->postJson('/kiosk/take', ['service_id' => $serviceA->id]);
        $resA2->assertStatus(200)->assertJsonPath('ticket.ticket_number', 'A-002');

        // 3. Take Service B -> starts at B-001
        $resB1 = $this->postJson('/kiosk/take', ['service_id' => $serviceB->id]);
        $resB1->assertStatus(200)->assertJsonPath('ticket.ticket_number', 'B-001');

        // 4. Take Service C -> starts at C-001
        $resC1 = $this->postJson('/kiosk/take', ['service_id' => $serviceC->id]);
        $resC1->assertStatus(200)->assertJsonPath('ticket.ticket_number', 'C-001');
    }

    public function test_operator_flow_call_recall_skip_complete(): void
    {
        $operator = User::where('role', 'petugas')->first();
        $counter = Counter::first();
        $serviceA = Service::where('code', 'A')->first();

        // Take a ticket first
        $resTake = $this->postJson('/kiosk/take', ['service_id' => $serviceA->id]);
        $resTake->assertStatus(200);

        // 1. Operator calls next ticket in queue (FIFO)
        $responseCall = $this->actingAs($operator)
            ->postJson('/operator/call-next', [
                'counter_id' => $counter->id,
                'service_id' => null,
            ]);

        $responseCall->assertStatus(200);
        $calledTicketId = $responseCall->json('ticket.id');

        $this->assertDatabaseHas('tickets', [
            'id' => $calledTicketId,
            'status' => 'called',
            'counter_id' => $counter->id,
            'call_count' => 1,
        ]);

        // 2. Operator recalls ticket
        $responseRecall = $this->actingAs($operator)
            ->postJson("/operator/recall/{$calledTicketId}", [
                'counter_id' => $counter->id,
            ]);

        $responseRecall->assertStatus(200);
        $this->assertDatabaseHas('tickets', [
            'id' => $calledTicketId,
            'call_count' => 2,
        ]);

        // 3. Operator completes ticket
        $responseComplete = $this->actingAs($operator)
            ->postJson("/operator/complete/{$calledTicketId}");

        $responseComplete->assertStatus(200);
        $this->assertDatabaseHas('tickets', [
            'id' => $calledTicketId,
            'status' => 'completed',
        ]);
    }

    public function test_tv_display_and_data_endpoint(): void
    {
        $responseTv = $this->get('/display');
        $responseTv->assertStatus(200);
        $responseTv->assertSee('BADAN PUSAT STATISTIK KABUPATEN KARANGANYAR');

        $responseData = $this->getJson('/display/data');
        $responseData->assertStatus(200);
        $responseData->assertJsonStructure([
            'timestamp',
            'totals' => ['total', 'waiting', 'called', 'completed', 'skipped'],
            'services',
            'recent_calls',
        ]);
    }

    public function test_admin_dashboard_and_report_export(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Ringkasan Eksekutif');

        // Test reports index page with date filtering (where AVG wait time is calculated)
        $responseReports = $this->actingAs($admin)->get('/admin/reports?start_date='.now()->toDateString().'&end_date='.now()->toDateString());
        $responseReports->assertStatus(200);
        $responseReports->assertSee('Rekapitulasi Antrean');

        $responseExport = $this->actingAs($admin)->get('/admin/reports/export');
        $responseExport->assertStatus(200);
        $this->assertTrue(str_contains($responseExport->headers->get('content-type'), 'text/csv'));
    }

    public function test_paperless_online_ticket_flow_and_feedback(): void
    {
        $service = Service::where('code', 'B')->first();

        // 1. Visit online page
        $responseOnline = $this->get('/online');
        $responseOnline->assertStatus(200);
        $responseOnline->assertSee('Paperless');

        // 2. Submit ticket via mobile online
        $responseStore = $this->postJson('/online', [
            'service_id' => $service->id,
            'visitor_name' => 'Budi Santoso',
            'visitor_institution' => 'Bappeda Karanganyar',
        ]);
        $responseStore->assertStatus(200);
        $responseStore->assertJsonStructure(['success', 'redirect_url', 'ticket' => ['number', 'service', 'token']]);

        $token = $responseStore->json('ticket.token');
        $this->assertNotNull($token);

        // 3. View live e-ticket
        $responseLive = $this->get('/tiket/'.$token);
        $responseLive->assertStatus(200);
        $responseLive->assertSee('Budi Santoso');
        $responseLive->assertSee('Bappeda Karanganyar');

        // 4. Polling status endpoint
        $responseStatus = $this->getJson('/tiket/'.$token.'/status');
        $responseStatus->assertStatus(200);
        $responseStatus->assertJson(['status' => 'waiting']);

        // 5. Submit survey feedback
        $responseFeedback = $this->postJson('/tiket/'.$token.'/feedback', [
            'feedback_rating' => 4,
            'feedback_comment' => 'Pelayanan ramah dan cepat!',
        ]);
        $responseFeedback->assertStatus(200);
        $responseFeedback->assertJson(['success' => true]);

        $ticket = Ticket::where('tracking_token', $token)->first();
        $this->assertEquals(4, $ticket->feedback_rating);
        $this->assertEquals('Pelayanan ramah dan cepat!', $ticket->feedback_comment);
    }

    public function test_standee_printable_qr_page_loads_successfully(): void
    {
        $response = $this->get('/standee');
        $response->assertStatus(200);
        $response->assertSee('AMBIL NOMOR ANTREAN DI SINI');
        $response->assertSee('Pelayanan Statistik Terpadu (PST)');

        $responseCetak = $this->get('/cetak-qr');
        $responseCetak->assertStatus(200);
        $responseCetak->assertSee('AMBIL NOMOR ANTREAN DI SINI');
    }

    public function test_admin_guide_page_loads_successfully_for_authenticated_users(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/guide');
        $response->assertStatus(200);
        $response->assertSee('Buku Panduan Sistem Antrean PST');
        $response->assertSee('Alur Pengunjung');
        $response->assertSee('Panduan Pegawai Loket');
        $response->assertSee('Panduan Administrator');

        $responseShortcut = $this->actingAs($admin)->get('/panduan');
        $responseShortcut->assertStatus(200);
        $responseShortcut->assertSee('Buku Panduan Sistem Antrean PST');
    }
}
