<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'counter_id',
        'user_id',
        'ticket_number',
        'queue_number',
        'service_date',
        'status',
        'called_at',
        'completed_at',
        'call_count',
        'notes',
        'visitor_name',
        'visitor_institution',
        'tracking_token',
        'feedback_rating',
        'feedback_comment',
    ];

    protected function casts(): array
    {
        return [
            'queue_number' => 'integer',
            'service_date' => 'date',
            'called_at' => 'datetime',
            'completed_at' => 'datetime',
            'call_count' => 'integer',
            'feedback_rating' => 'integer',
        ];
    }

    /**
     * Get count of tickets waiting ahead of this ticket for the same service today.
     */
    public function getWaitingAheadCountAttribute(): int
    {
        if ($this->status !== 'waiting') {
            return 0;
        }

        return self::where('service_id', $this->service_id)
            ->whereDate('service_date', $this->service_date)
            ->where('status', 'waiting')
            ->where('queue_number', '<', $this->queue_number)
            ->count();
    }

    /**
     * Get human readable feedback label.
     */
    public function getFeedbackLabelAttribute(): ?string
    {
        return match ($this->feedback_rating) {
            4 => 'Sangat Puas',
            3 => 'Puas',
            2 => 'Cukup',
            1 => 'Tidak Puas',
            default => null,
        };
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function counter(): BelongsTo
    {
        return $this->belongsTo(Counter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function callHistories(): HasMany
    {
        return $this->hasMany(CallHistory::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('service_date', Carbon::today());
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeCalled($query)
    {
        return $query->where('status', 'called');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeSkipped($query)
    {
        return $query->where('status', 'skipped');
    }

    /**
     * Get speech synthesis text in Indonesian
     * Example: "Nomor antrean A nol nol satu, pelayanan perpustakaan, silakan menuju loket satu"
     */
    public function getSpokenTextAttribute(): string
    {
        $code = $this->service ? $this->service->code : substr($this->ticket_number, 0, 1);
        $num = (int) $this->queue_number;

        // Spell number: e.g. 001 -> nol nol satu, 012 -> nol satu dua, 105 -> satu nol lima
        $formattedNum = str_pad($num, 3, '0', STR_PAD_LEFT);
        $digitWords = [
            '0' => 'nol',
            '1' => 'satu',
            '2' => 'dua',
            '3' => 'tiga',
            '4' => 'empat',
            '5' => 'lima',
            '6' => 'enam',
            '7' => 'tujuh',
            '8' => 'delapan',
            '9' => 'sembilan',
        ];

        $spelledDigits = [];
        for ($i = 0; $i < strlen($formattedNum); $i++) {
            $char = $formattedNum[$i];
            $spelledDigits[] = $digitWords[$char] ?? $char;
        }
        $digitsText = implode(' ', $spelledDigits);

        $serviceName = strtolower($this->service ? $this->service->name : 'layanan');

        $counterRaw = $this->counter ? $this->counter->name : 'loket satu';
        // Convert digits in counter name to words: e.g. "Loket 1" -> "loket satu"
        $counterWords = str_replace(
            ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'],
            ['satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'nol'],
            $counterRaw
        );
        $counterName = strtolower($counterWords);

        return "Nomor antrean {$code} {$digitsText}, {$serviceName}, silakan menuju {$counterName}.";
    }

    /**
     * Waiting duration in minutes
     */
    public function getWaitDurationInMinutesAttribute(): ?int
    {
        if ($this->called_at) {
            return $this->created_at->diffInMinutes($this->called_at);
        }

        return null;
    }

    /**
     * Service duration in minutes
     */
    public function getServiceDurationInMinutesAttribute(): ?int
    {
        if ($this->called_at && $this->completed_at) {
            return $this->called_at->diffInMinutes($this->completed_at);
        }

        return null;
    }
}
