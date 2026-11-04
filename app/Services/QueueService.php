<?php

namespace App\Services;

use App\Events\QueueUpdated;
use App\Events\TicketCalled;
use App\Models\CallHistory;
use App\Models\Counter;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QueueService
{
    /**
     * Create a new ticket safely with lockForUpdate to prevent race conditions.
     */
    public function createTicket(Service $service, ?string $visitorName = null, ?string $visitorInstitution = null): Ticket
    {
        return DB::transaction(function () use ($service, $visitorName, $visitorInstitution) {
            $today = Carbon::today()->toDateString();

            // Lock max queue number for this service today
            $lastQueue = Ticket::where('service_id', $service->id)
                ->whereDate('service_date', $today)
                ->lockForUpdate()
                ->max('queue_number');

            $nextNumber = ($lastQueue ?? 0) + 1;
            $formattedNumber = $service->code.'-'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $ticket = Ticket::create([
                'service_id' => $service->id,
                'ticket_number' => $formattedNumber,
                'queue_number' => $nextNumber,
                'service_date' => $today,
                'status' => 'waiting',
                'call_count' => 0,
                'visitor_name' => $visitorName,
                'visitor_institution' => $visitorInstitution,
                'tracking_token' => Str::random(32),
            ]);

            // Dispatch update event
            broadcast(new QueueUpdated)->toOthers();

            return $ticket;
        });
    }

    /**
     * Call the next waiting ticket safely with row-level locking.
     */
    public function callNext(Counter $counter, User $user, ?int $serviceId = null): ?Ticket
    {
        return DB::transaction(function () use ($counter, $user, $serviceId) {
            $today = Carbon::today()->toDateString();

            $query = Ticket::whereDate('service_date', $today)
                ->where('status', 'waiting');

            if ($serviceId) {
                $query->where('service_id', $serviceId);
            }

            // Lock the first waiting ticket
            $ticket = $query->orderBy('created_at', 'asc')
                ->lockForUpdate()
                ->first();

            if (! $ticket) {
                return null;
            }

            $ticket->update([
                'status' => 'called',
                'counter_id' => $counter->id,
                'user_id' => $user->id,
                'called_at' => now(),
                'call_count' => $ticket->call_count + 1,
            ]);

            // Record call history
            CallHistory::create([
                'ticket_id' => $ticket->id,
                'service_id' => $ticket->service_id,
                'counter_id' => $counter->id,
                'user_id' => $user->id,
                'call_type' => 'call',
                'called_at' => now(),
            ]);

            // Refresh model with relations for broadcasting and response
            $ticket->load(['service', 'counter', 'user']);

            // Fire real-time broadcast
            broadcast(new TicketCalled($ticket, 'call'));
            broadcast(new QueueUpdated);

            return $ticket;
        });
    }

    /**
     * Recall a ticket that is currently called.
     */
    public function recall(Ticket $ticket, Counter $counter, User $user): Ticket
    {
        return DB::transaction(function () use ($ticket, $counter, $user) {
            $ticket->update([
                'status' => 'called',
                'counter_id' => $counter->id,
                'user_id' => $user->id,
                'called_at' => now(),
                'call_count' => $ticket->call_count + 1,
            ]);

            CallHistory::create([
                'ticket_id' => $ticket->id,
                'service_id' => $ticket->service_id,
                'counter_id' => $counter->id,
                'user_id' => $user->id,
                'call_type' => 'recall',
                'called_at' => now(),
            ]);

            $ticket->load(['service', 'counter', 'user']);

            broadcast(new TicketCalled($ticket, 'recall'));
            broadcast(new QueueUpdated);

            return $ticket;
        });
    }

    /**
     * Mark a ticket as completed.
     */
    public function complete(Ticket $ticket, ?string $notes = null): Ticket
    {
        $ticket->update([
            'status' => 'completed',
            'completed_at' => now(),
            'notes' => $notes ?: $ticket->notes,
        ]);

        broadcast(new QueueUpdated);

        return $ticket;
    }

    /**
     * Mark a ticket as skipped.
     */
    public function skip(Ticket $ticket, ?string $notes = null): Ticket
    {
        $ticket->update([
            'status' => 'skipped',
            'completed_at' => now(),
            'notes' => $notes ?: 'Pengunjung tidak hadir saat dipanggil',
        ]);

        broadcast(new QueueUpdated);

        return $ticket;
    }

    /**
     * Get real-time queue snapshot for TV display and dashboard.
     */
    public function getTodaySnapshot(): array
    {
        $today = Carbon::today()->toDateString();

        $activeServices = Service::active()->get();
        $serviceStats = [];

        foreach ($activeServices as $service) {
            $total = Ticket::where('service_id', $service->id)->whereDate('service_date', $today)->count();
            $waiting = Ticket::where('service_id', $service->id)->whereDate('service_date', $today)->where('status', 'waiting')->count();
            $current = Ticket::where('service_id', $service->id)->whereDate('service_date', $today)->where('status', 'called')->latest('called_at')->first();
            $completed = Ticket::where('service_id', $service->id)->whereDate('service_date', $today)->where('status', 'completed')->count();

            $serviceStats[] = [
                'id' => $service->id,
                'code' => $service->code,
                'name' => $service->name,
                'color' => $service->color,
                'icon' => $service->icon,
                'total_today' => $total,
                'waiting_count' => $waiting,
                'current_ticket' => $current ? $current->ticket_number : '-',
                'current_counter' => $current && $current->counter ? $current->counter->name : '-',
                'completed_count' => $completed,
            ];
        }

        // Active ticket currently being called across any counter
        $lastCalledTicket = Ticket::with(['service', 'counter', 'user'])
            ->whereDate('service_date', $today)
            ->where('status', 'called')
            ->orderBy('called_at', 'desc')
            ->first();

        // Recently called list (last 5 called or serving)
        $recentCalls = Ticket::with(['service', 'counter'])
            ->whereDate('service_date', $today)
            ->whereIn('status', ['called', 'completed'])
            ->whereNotNull('called_at')
            ->orderBy('called_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'ticket_number' => $t->ticket_number,
                    'service_name' => $t->service->name,
                    'service_code' => $t->service->code,
                    'counter_name' => $t->counter ? $t->counter->name : '-',
                    'status' => $t->status,
                    'called_at' => $t->called_at ? $t->called_at->format('H:i') : '-',
                ];
            });

        // Totals
        $totalToday = Ticket::whereDate('service_date', $today)->count();
        $waitingToday = Ticket::whereDate('service_date', $today)->where('status', 'waiting')->count();
        $calledToday = Ticket::whereDate('service_date', $today)->where('status', 'called')->count();
        $completedToday = Ticket::whereDate('service_date', $today)->where('status', 'completed')->count();
        $skippedToday = Ticket::whereDate('service_date', $today)->where('status', 'skipped')->count();

        return [
            'timestamp' => now()->toIso8601String(),
            'formatted_time' => now()->format('H:i:s'),
            'formatted_date' => now()->translatedFormat('l, d F Y'),
            'totals' => [
                'total' => $totalToday,
                'waiting' => $waitingToday,
                'called' => $calledToday,
                'completed' => $completedToday,
                'skipped' => $skippedToday,
            ],
            'last_called' => $lastCalledTicket ? [
                'id' => $lastCalledTicket->id,
                'ticket_number' => $lastCalledTicket->ticket_number,
                'service_name' => $lastCalledTicket->service->name,
                'service_code' => $lastCalledTicket->service->code,
                'service_color' => $lastCalledTicket->service->color,
                'counter_name' => $lastCalledTicket->counter ? $lastCalledTicket->counter->name : 'Loket 1',
                'counter_number' => $lastCalledTicket->counter ? $lastCalledTicket->counter->number : 1,
                'spoken_text' => $lastCalledTicket->spoken_text,
                'call_count' => $lastCalledTicket->call_count,
                'called_at' => $lastCalledTicket->called_at ? $lastCalledTicket->called_at->format('H:i:s') : null,
                'called_at_timestamp' => $lastCalledTicket->called_at ? $lastCalledTicket->called_at->timestamp : 0,
            ] : null,
            'services' => $serviceStats,
            'recent_calls' => $recentCalls,
        ];
    }

    /**
     * Get the public URL for online mobile ticket kiosk (auto-resolving LAN IP for phone scanners).
     */
    public function getPublicOnlineUrl(): string
    {
        $customUrl = Setting::get('public_online_url');
        if (! empty($customUrl)) {
            return rtrim($customUrl, '/');
        }

        $currentHost = request()->getHost();
        $currentPort = request()->getPort();
        $scheme = request()->getScheme();

        // If accessed through localhost or 127.0.0.1, resolve the server's local LAN IPv4 address
        if (in_array($currentHost, ['localhost', '127.0.0.1', '::1'])) {
            $lanIp = $this->detectLocalLanIp();
            if ($lanIp) {
                $portStr = ($currentPort && ! in_array($currentPort, [80, 443])) ? ":{$currentPort}" : '';

                return "{$scheme}://{$lanIp}{$portStr}/online";
            }
        }

        return route('ticket.online');
    }

    /**
     * Detect local LAN IP address (excluding loopback and virtual adapter subnets).
     */
    public function detectLocalLanIp(): ?string
    {
        $ips = gethostbynamel(gethostname()) ?: [];
        foreach ($ips as $ip) {
            if (str_starts_with($ip, '127.') || str_starts_with($ip, '169.254.') || str_starts_with($ip, '192.168.56.')) {
                continue;
            }
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                return $ip;
            }
        }

        return $ips[0] ?? null;
    }
}
