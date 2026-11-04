<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Setting;
use App\Models\Ticket;
use App\Services\QueueService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KioskController extends Controller
{
    public function __construct(
        protected QueueService $queueService
    ) {}

    /**
     * Display the ticket kiosk landing page.
     */
    public function index(): View
    {
        $services = Service::active()->get();
        $officeName = Setting::get('office_name', 'BPS KABUPATEN KARANGANYAR');
        $appTitle = Setting::get('app_title', 'Sistem Antrean Pelayanan Statistik Terpadu');
        $autoRedirect = (int) Setting::get('ticket_auto_redirect_seconds', 6);
        $onlineUrl = $this->queueService->getPublicOnlineUrl();

        // Get count waiting for each service today
        $today = Carbon::today()->toDateString();
        $waitingCounts = [];
        foreach ($services as $service) {
            $waitingCounts[$service->id] = Ticket::where('service_id', $service->id)
                ->whereDate('service_date', $today)
                ->where('status', 'waiting')
                ->count();
        }

        return view('kiosk.index', compact('services', 'officeName', 'appTitle', 'autoRedirect', 'waitingCounts', 'onlineUrl'));
    }

    /**
     * Printable Standing Acrylic / Poster QR code for lobby desk.
     */
    public function standee(): View
    {
        $onlineUrl = $this->queueService->getPublicOnlineUrl();
        $officeName = Setting::get('office_name', 'BADAN PUSAT STATISTIK KABUPATEN KARANGANYAR');
        $officeAddress = Setting::get('office_address', 'Jl. Lawu No. 385, Karanganyar');
        $services = Service::active()->get();

        return view('kiosk.standee', compact('onlineUrl', 'officeName', 'officeAddress', 'services'));
    }

    /**
     * Generate a new queue ticket for the selected service.
     */
    public function takeTicket(Request $request): JsonResponse
    {
        $request->validate([
            'service_id' => ['required', 'exists:services,id'],
        ]);

        $service = Service::where('id', $request->service_id)
            ->where('is_active', true)
            ->firstOrFail();

        $ticket = $this->queueService->createTicket($service);
        $ticket->load('service');

        $officeName = Setting::get('office_name', 'BPS KABUPATEN KARANGANYAR');
        $officeAddress = Setting::get('office_address', 'Jl. Lawu No. 385, Karanganyar');
        $footerNote = Setting::get('ticket_footer_note', 'Harap menunggu hingga nomor antrean Anda dipanggil.');

        return response()->json([
            'success' => true,
            'message' => 'Nomor antrean berhasil dibuat.',
            'ticket' => [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'service_code' => $ticket->service->code,
                'service_name' => $ticket->service->name,
                'date' => $ticket->created_at->translatedFormat('d F Y'),
                'time' => $ticket->created_at->format('H.i').' WIB',
                'office_name' => $officeName,
                'office_address' => $officeAddress,
                'footer_note' => $footerNote,
                'tracking_token' => $ticket->tracking_token,
                'tracking_url' => route('ticket.live', ['token' => $ticket->tracking_token]),
                'waiting_count_ahead' => Ticket::where('service_id', $service->id)
                    ->whereDate('service_date', $ticket->service_date)
                    ->where('status', 'waiting')
                    ->where('id', '<', $ticket->id)
                    ->count(),
            ],
        ]);
    }

    /**
     * Direct print view for thermal printer (58mm / 80mm).
     */
    public function print(Ticket $ticket): View
    {
        $ticket->load('service');
        $officeName = Setting::get('office_name', 'BPS KABUPATEN KARANGANYAR');
        $officeAddress = Setting::get('office_address', 'Jl. Lawu No. 385, Karanganyar');
        $footerNote = Setting::get('ticket_footer_note', 'Harap menunggu hingga nomor antrean Anda dipanggil.');

        return view('kiosk.print', compact('ticket', 'officeName', 'officeAddress', 'footerNote'));
    }
}
