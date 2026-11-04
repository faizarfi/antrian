<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'code' => 'A',
                'name' => 'Pelayanan Perpustakaan',
                'slug' => 'pelayanan-perpustakaan',
                'description' => 'Untuk pengunjung yang ingin membaca, mengakses, atau mendapatkan publikasi statistik BPS.',
                'color' => '#0093DD', // Biru Resmi BPS (Sensus Penduduk)
                'icon' => 'book-open',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'code' => 'B',
                'name' => 'Pelayanan Konsultasi Statistik',
                'slug' => 'pelayanan-konsultasi-statistik',
                'description' => 'Untuk pengunjung yang membutuhkan konsultasi data, konsep statistik, metadata, klasifikasi, maupun informasi statistik lainnya.',
                'color' => '#68B92E', // Hijau Resmi BPS (Sensus Pertanian)
                'icon' => 'bar-chart-2',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'code' => 'C',
                'name' => 'Pelayanan Rekomendasi Kegiatan Statistik (Romantik)',
                'slug' => 'pelayanan-romantik',
                'description' => 'Untuk instansi pemerintah atau OPD yang membutuhkan pelayanan rekomendasi kegiatan statistik sektoral.',
                'color' => '#EB891B', // Oranye Resmi BPS (Sensus Ekonomi)
                'icon' => 'file-check',
                'is_active' => true,
                'order' => 3,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['code' => $service['code']], $service);
        }
    }
}
