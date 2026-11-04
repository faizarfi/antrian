<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            [
                'key' => 'office_name',
                'value' => 'BADAN PUSAT STATISTIK KABUPATEN KARANGANYAR',
                'group' => 'general',
                'description' => 'Nama Satuan Kerja / Kantor BPS',
            ],
            [
                'key' => 'app_title',
                'value' => 'Sistem Antrean Pelayanan Statistik Terpadu',
                'group' => 'general',
                'description' => 'Judul Aplikasi Pelayanan',
            ],
            [
                'key' => 'office_address',
                'value' => 'Jl. Lawu No. 385, Badran Asri, Karanganyar, Jawa Tengah 57712',
                'group' => 'general',
                'description' => 'Alamat Kantor BPS',
            ],
            [
                'key' => 'office_phone',
                'value' => '(0271) 495023',
                'group' => 'general',
                'description' => 'Nomor Telepon Kantor',
            ],
            // TV Display
            [
                'key' => 'running_text',
                'value' => 'Selamat Datang di Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar • Seluruh Pelayanan BPS Bebas Biaya (GRATIS) • Melayani dengan Hati Sepenuh Jiwa • Maklumat Pelayanan: Memberikan Pelayanan Cepat, Tepat, dan Akurat • Tolak Gratifikasi dan Pungutan Liar! • Statistik Berkualitas untuk Indonesia Maju',
                'group' => 'tv',
                'description' => 'Teks Berjalan di Footer TV Display',
            ],
            [
                'key' => 'tv_welcome_title',
                'value' => 'Pelayanan Statistik Terpadu (PST)',
                'group' => 'tv',
                'description' => 'Pesan Selamat Datang TV saat Siaga',
            ],
            [
                'key' => 'tv_welcome_subtitle',
                'value' => 'BPS Kabupaten Karanganyar — Penyedia Data Statistik Berkualitas untuk Indonesia Maju',
                'group' => 'tv',
                'description' => 'Subjudul Pesan TV saat Siaga',
            ],
            // Audio settings
            [
                'key' => 'audio_enabled',
                'value' => '1',
                'group' => 'audio',
                'description' => 'Status Audio Pemanggilan (1: Aktif, 0: Nonaktif)',
            ],
            [
                'key' => 'audio_chime',
                'value' => '1',
                'group' => 'audio',
                'description' => 'Bunyi Nada Pembuka / Bell Chime sebelum Panggilan (1: Aktif, 0: Nonaktif)',
            ],
            [
                'key' => 'audio_voice_rate',
                'value' => '0.9',
                'group' => 'audio',
                'description' => 'Kecepatan Suara Bicara (0.5 - 1.5, default: 0.9)',
            ],
            [
                'key' => 'audio_voice_pitch',
                'value' => '1.0',
                'group' => 'audio',
                'description' => 'Tinggi Nada Suara Bicara (0.5 - 1.5, default: 1.0)',
            ],
            [
                'key' => 'audio_volume',
                'value' => '1.0',
                'group' => 'audio',
                'description' => 'Volume Suara Pemanggilan (0.0 - 1.0, default: 1.0)',
            ],
            // Ticket
            [
                'key' => 'ticket_auto_redirect_seconds',
                'value' => '6',
                'group' => 'ticket',
                'description' => 'Waktu hitung mundur kembali otomatis setelah ambil tiket (detik)',
            ],
            [
                'key' => 'ticket_footer_note',
                'value' => 'Harap menunggu hingga nomor antrean Anda dipanggil di layar monitor.',
                'group' => 'ticket',
                'description' => 'Catatan kaki pada cetakan tiket thermal',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
