<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Setting;
use App\Models\Ticket;
use App\Services\QueueService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnlineTicketController extends Controller
{
    public function __construct(
        protected QueueService $queueService
    ) {}

    /**
     * Display the mobile paperless ticket registration page.
     */
    public function index(): View
    {
        $services = Service::active()->get();
        $officeName = Setting::get('office_name', 'BPS KABUPATEN KARANGANYAR');
        $officeAddress = Setting::get('office_address', 'Jl. Lawu No. 385, Karanganyar');
        $appTitle = Setting::get('app_title', 'Sistem Antrean Pelayanan Statistik Terpadu');

        $today = Carbon::today()->toDateString();
        $waitingCounts = [];
        foreach ($services as $service) {
            $waitingCounts[$service->id] = Ticket::where('service_id', $service->id)
                ->whereDate('service_date', $today)
                ->where('status', 'waiting')
                ->count();
        }

        return view('tickets.online', compact(
            'services',
            'officeName',
            'officeAddress',
            'appTitle',
            'waitingCounts'
        ));
    }

    /**
     * Issue an online paperless ticket from mobile.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'visitor_name' => ['nullable', 'string', 'max:100'],
            'visitor_institution' => ['nullable', 'string', 'max:150'],
        ]);

        $service = Service::where('id', $validated['service_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $ticket = $this->queueService->createTicket(
            $service,
            $validated['visitor_name'] ?? null,
            $validated['visitor_institution'] ?? null
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('ticket.live', ['token' => $ticket->tracking_token]),
                'ticket' => [
                    'number' => $ticket->ticket_number,
                    'service' => $service->name,
                    'token' => $ticket->tracking_token,
                ],
            ]);
        }

        return redirect()->route('ticket.live', ['token' => $ticket->tracking_token]);
    }

    /**
     * Show live digital ticket on visitor mobile phone.
     */
    public function live(string $token): View
    {
        $ticket = Ticket::with(['service', 'counter', 'user'])
            ->where('tracking_token', $token)
            ->firstOrFail();

        $officeName = Setting::get('office_name', 'BPS KABUPATEN KARANGANYAR');
        $waitingAhead = $ticket->waiting_ahead_count;

        return view('tickets.live', compact('ticket', 'officeName', 'waitingAhead'));
    }

    /**
     * Realtime JSON status for polling from mobile screen.
     */
    public function status(string $token): JsonResponse
    {
        $ticket = Ticket::with(['service', 'counter'])
            ->where('tracking_token', $token)
            ->firstOrFail();

        return response()->json([
            'status' => $ticket->status,
            'ticket_number' => $ticket->ticket_number,
            'service_name' => $ticket->service->name,
            'service_color' => $ticket->service->color,
            'counter_name' => $ticket->counter ? $ticket->counter->name : null,
            'called_at' => $ticket->called_at ? $ticket->called_at->format('H:i').' WIB' : null,
            'call_count' => $ticket->call_count,
            'waiting_ahead' => $ticket->waiting_ahead_count,
            'feedback_submitted' => ! is_null($ticket->feedback_rating),
        ]);
    }

    /**
     * Submit visitor satisfaction survey (SKM / IKM).
     */
    public function submitFeedback(Request $request, string $token): JsonResponse
    {
        $validated = $request->validate([
            'feedback_rating' => ['required', 'integer', 'between:1,4'],
            'feedback_comment' => ['nullable', 'string', 'max:500'],
        ]);

        $ticket = Ticket::where('tracking_token', $token)->firstOrFail();
        $ticket->update([
            'feedback_rating' => $validated['feedback_rating'],
            'feedback_comment' => $validated['feedback_comment'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Penilaian Anda sangat berharga untuk meningkatkan kualitas pelayanan PST BPS Kabupaten Karanganyar.',
        ]);
    }
}
