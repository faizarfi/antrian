<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'Pelayanan Gratis',
                'content' => 'Seluruh pelayanan di Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar TIDAK DIPUNGUT BIAYA (GRATIS). Tolak Gratifikasi!',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'Jam Layanan',
                'content' => 'Jam Pelayanan Terpadu: Senin s.d. Kamis pukul 08.00 - 15.30 WIB | Jumat pukul 08.00 - 15.00 WIB.',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'title' => 'Maklumat Pelayanan',
                'content' => 'Dengan ini kami menyatakan sanggup menyelenggarakan pelayanan statistik sesuai standar pelayanan yang telah ditetapkan.',
                'is_active' => true,
                'order' => 3,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::updateOrCreate(['title' => $announcement['title']], $announcement);
        }
    }
}
