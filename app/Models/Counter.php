<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Counter extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'number' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function callHistories(): HasMany
    {
        return $this->hasMany(CallHistory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('number', 'asc');
    }
}
