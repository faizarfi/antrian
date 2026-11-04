<?php

namespace Database\Seeders;

use App\Models\Counter;
use Illuminate\Database\Seeder;

class CounterSeeder extends Seeder
{
    public function run(): void
    {
        // 1 Loket Pelayanan Terpadu untuk melayani 3 Layanan (A, B, C)
        Counter::updateOrCreate(
            ['number' => 1],
            [
                'name' => 'Loket 1',
                'description' => 'Loket Pelayanan Terpadu (Perpustakaan, Konsultasi Statistik, Romantik)',
                'is_active' => true,
            ]
        );

        // Pastikan hanya ada 1 loket aktif di sistem
        Counter::where('number', '>', 1)->delete();
    }
}
