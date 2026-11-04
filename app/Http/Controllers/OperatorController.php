<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Service;
use App\Models\Ticket;
use App\Services\QueueService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OperatorController extends Controller
{
    public function __construct(
        protected QueueService $queueService
    ) {}

    /**
     * Operator dashboard main page.
     */
    public function index(Request $request): View
    {
        $today = Carbon::today()->toDateString();
        $counters = Counter::active()->get();
        $services = Service::active()->get();

        // Selected counter and service from session or query
        $selectedCounterId = $request->session()->get('operator_counter_id', $counters->first()?->id);
        $selectedServiceId = $request->session()->get('operator_service_id', null);

        $selectedCounter = Counter::find($selectedCounterId) ?? $counters->first();

        // Current ticket being served by this operator/counter
        $currentTicket = Ticket::with(['service', 'counter', 'user'])
            ->whereDate('service_date', $today)
            ->where('counter_id', $selectedCounter?->id)
            ->where('status', 'called')
            ->latest('called_at')
            ->first();

        // Stats today
        $stats = [
            'total' => Ticket::whereDate('service_date', $today)->count(),
            'waiting' => Ticket::whereDate('service_date', $today)->where('status', 'waiting')->count(),
            'called' => Ticket::whereDate('service_date', $today)->where('status', 'called')->count(),
            'completed' => Ticket::whereDate('service_date', $today)->where('status', 'completed')->count(),
            'skipped' => Ticket::whereDate('service_date', $today)->where('status', 'skipped')->count(),
        ];

        // Service queues breakdown
        $serviceQueues = [];
        foreach ($services as $service) {
            $waitingList = Ticket::where('service_id', $service->id)
                ->whereDate('service_date', $today)
                ->where('status', 'waiting')
                ->orderBy('created_at', 'asc')
                ->take(10)
                ->get();

            $serviceQueues[] = [
                'service' => $service,
                'waiting_count' => Ticket::where('service_id', $service->id)
                    ->whereDate('service_date', $today)
                    ->where('status', 'waiting')
                    ->count(),
                'waiting_list' => $waitingList,
                'last_called' => Ticket::where('service_id', $service->id)
                    ->whereDate('service_date', $today)
                    ->where('status', 'called')
                    ->latest('called_at')
                    ->first(),
            ];
        }

        // Today's call history for this operator or counter
        $recentHistories = Ticket::with(['service', 'counter', 'user'])
            ->whereDate('service_date', $today)
            ->whereIn('status', ['called', 'completed', 'skipped'])
            ->where('counter_id', $selectedCounter?->id)
            ->orderBy('updated_at', 'desc')
            ->take(15)
            ->get();

        return view('operator.index', compact(
            'counters',
            'services',
            'selectedCounter',
            'selectedServiceId',
            'currentTicket',
            'stats',
            'serviceQueues',
            'recentHistories'
        ));
    }

    /**
     * Update active counter or service assignment in session.
     */
    public function setPreferences(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'counter_id' => ['required', 'exists:counters,id'],
            'service_id' => ['nullable', 'exists:services,id'],
        ]);

        $request->session()->put('operator_counter_id', $validated['counter_id']);
        $request->session()->put('operator_service_id', $validated['service_id'] ?? null);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan loket dan layanan berhasil diperbarui.',
        ]);
    }

    /**
     * Call next waiting ticket.
     */
    public function callNext(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'counter_id' => ['required', 'exists:counters,id'],
            'service_id' => ['nullable', 'exists:services,id'],
        ]);

        $counter = Counter::findOrFail($validated['counter_id']);
        $user = Auth::user();
        $serviceId = ! empty($validated['service_id']) ? (int) $validated['service_id'] : null;

        // Auto-complete or handle previous called ticket if any exists on this counter
        $previousTicket = Ticket::whereDate('service_date', Carbon::today())
            ->where('counter_id', $counter->id)
            ->where('status', 'called')
            ->first();

        if ($previousTicket) {
            $this->queueService->complete($previousTicket, 'Selesai otomatis saat memanggil nomor berikutnya');
        }

        $ticket = $this->queueService->callNext($counter, $user, $serviceId);

        if (! $ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada antrean yang sedang menunggu saat ini.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Nomor antrean '.$ticket->ticket_number.' dipanggil.',
            'ticket' => [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'service_name' => $ticket->service->name,
                'service_code' => $ticket->service->code,
                'counter_name' => $ticket->counter->name,
                'call_count' => $ticket->call_count,
                'called_at' => $ticket->called_at->format('H:i:s'),
                'spoken_text' => $ticket->spoken_text,
                'visitor_name' => $ticket->visitor_name,
                'visitor_institution' => $ticket->visitor_institution,
            ],
        ]);
    }

    /**
     * Recall currently active ticket.
     */
    public function recall(Ticket $ticket, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'counter_id' => ['required', 'exists:counters,id'],
        ]);

        $counter = Counter::findOrFail($validated['counter_id']);
        $user = Auth::user();

        $updatedTicket = $this->queueService->recall($ticket, $counter, $user);

        return response()->json([
            'success' => true,
            'message' => 'Nomor antrean '.$updatedTicket->ticket_number.' berhasil dipanggil ulang.',
            'ticket' => [
                'id' => $updatedTicket->id,
                'ticket_number' => $updatedTicket->ticket_number,
                'service_name' => $updatedTicket->service->name,
                'counter_name' => $counter->name,
                'call_count' => $updatedTicket->call_count,
                'spoken_text' => $updatedTicket->spoken_text,
            ],
        ]);
    }

    /**
     * Complete a ticket service.
     */
    public function complete(Ticket $ticket, Request $request): JsonResponse
    {
        $notes = $request->input('notes');
        $updatedTicket = $this->queueService->complete($ticket, $notes);

        return response()->json([
            'success' => true,
            'message' => 'Pelayanan tiket '.$updatedTicket->ticket_number.' berhasil diselesaikan.',
        ]);
    }

    /**
     * Skip a ticket (visitor absent).
     */
    public function skip(Ticket $ticket, Request $request): JsonResponse
    {
        $notes = $request->input('notes');
        $updatedTicket = $this->queueService->skip($ticket, $notes);

        return response()->json([
            'success' => true,
            'message' => 'Tiket '.$updatedTicket->ticket_number.' ditandai dilewati (tidak hadir).',
        ]);
    }

    /**
     * Refresh operator queue table data via JSON.
     */
    public function refreshData(Request $request): JsonResponse
    {
        $today = Carbon::today()->toDateString();
        $counterId = $request->input('counter_id');

        $currentTicket = null;
        if ($counterId) {
            $currentTicket = Ticket::with(['service', 'counter', 'user'])
                ->whereDate('service_date', $today)
                ->where('counter_id', $counterId)
                ->where('status', 'called')
                ->latest('called_at')
                ->first();
        }

        $snapshot = $this->queueService->getTodaySnapshot();

        return response()->json([
            'success' => true,
            'current_ticket' => $currentTicket,
            'snapshot' => $snapshot,
        ]);
    }
}
