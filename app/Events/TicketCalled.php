<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketCalled implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public string $callType = 'call' // 'call' or 'recall'
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('queue-display'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ticket.called';
    }

    public function broadcastWith(): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'service_name' => $this->ticket->service->name,
            'service_code' => $this->ticket->service->code,
            'counter_name' => $this->ticket->counter ? $this->ticket->counter->name : 'Loket 1',
            'counter_number' => $this->ticket->counter ? $this->ticket->counter->number : 1,
            'operator_name' => $this->ticket->user ? $this->ticket->user->name : 'Petugas',
            'spoken_text' => $this->ticket->spoken_text,
            'call_type' => $this->callType,
            'called_at' => $this->ticket->called_at ? $this->ticket->called_at->toIso8601String() : now()->toIso8601String(),
        ];
    }
}
