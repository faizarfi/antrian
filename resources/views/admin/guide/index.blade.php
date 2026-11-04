@extends('layouts.admin')

@section('title', 'Buku Panduan Penggunaan Sistem - BPS Kabupaten Karanganyar')
@section('page_title', 'Panduan Penggunaan Sistem Antrean')

@push('styles')
<style>
    /* Animated floating badges & pulse */
    @keyframes subtle-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }
    .animate-float {
        animation: subtle-float 3s ease-in-out infinite;
    }

    /* Print styles for official SOP handbook export */
    @media print {
        aside, header, #mobile-sidebar, .no-print, .tab-nav {
            display: none !important;
        }
        body, main {
            padding: 0 !important;
            margin: 0 !important;
            background: #ffffff !important;
        }
        .tab-content, .tab-content.hidden {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            margin-bottom: 2rem !important;
            page-break-after: auto !important;
        }
        .hero-banner {
            background: #002f55 !important;
            color: #ffffff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6 max-w-6xl mx-auto pb-12">

    <!-- Official Print Header (Visible ONLY when printing / saving to PDF) -->
    <div class="hidden print:block border-b-2 border-slate-900 pb-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/bps-logo.svg') }}" alt="Logo BPS" class="h-14 w-auto object-contain">
                <div>
                    <h1 class="text-base font-black uppercase text-slate-900 leading-tight">BADAN PUSAT STATISTIK KABUPATEN KARANGANYAR</h1>
                    <div class="text-xs font-bold text-[#00529C]">PELAYANAN STATISTIK TERPADU (PST)</div>
                    <div class="text-[11px] text-slate-600">Buku Panduan & Standar Operasional Prosedur (SOP) Digital</div>
                </div>
            </div>
            <div class="text-right text-xs text-slate-600 font-mono">
                <div>Versi: 2.0 (Resmi)</div>
                <div>Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
            </div>
        </div>
    </div>

    <!-- Hero Header Banner with Visual Elements -->
    <div class="hero-banner bg-gradient-to-r from-[#002f55] via-[#003B70] to-[#00529C] rounded-3xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden">
        <!-- Ambient decorative shapes -->
        <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-white/5 pointer-events-none blur-2xl"></div>
        <div class="absolute right-32 top-0 w-32 h-32 rounded-full bg-[#0093DD]/20 pointer-events-none blur-xl"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-sky-200 border border-white/15 mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Standar Operasional Prosedur (SOP) Digital BPS</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white font-sans uppercase">
                    Buku Panduan Sistem Antrean PST
                </h1>
                <p class="text-xs sm:text-sm text-slate-200 mt-2 leading-relaxed">
                    Pedoman lengkap tata cara operasional aplikasi Sistem Antrean Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar bagi Pengunjung, Pegawai Loket, dan Administrator.
                </p>
            </div>

            <!-- Quick Action Toolbar -->
            <div class="flex flex-wrap sm:flex-col gap-2.5 shrink-0 no-print">
                <button type="button" onclick="window.print()"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold bg-white text-[#002f55] hover:bg-slate-100 transition-colors shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                    <i data-lucide="printer" class="w-4 h-4 text-[#0093DD]"></i>
                    <span>Cetak Buku Panduan</span>
                </button>
                <a href="{{ route('kiosk.standee') }}" target="_blank"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold bg-white/15 hover:bg-white/25 text-white border border-white/20 transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="qr-code" class="w-4 h-4 text-sky-300"></i>
                    <span>Cetak Standee QR</span>
                </a>
            </div>
        </div>

        <!-- 3 Role Quick Highlights -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-6 pt-6 border-t border-white/15 relative z-10 text-xs">
            <div class="flex items-center gap-3 bg-white/10 rounded-2xl p-3 border border-white/10">
                <div class="w-9 h-9 rounded-xl bg-amber-400/20 text-amber-300 flex items-center justify-center shrink-0">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="font-bold text-white">Untuk Pengunjung</div>
                    <div class="text-[11px] text-slate-300">Ambil e-Tiket & Pantau via HP</div>
                </div>
            </div>

            <div class="flex items-center gap-3 bg-white/10 rounded-2xl p-3 border border-white/10">
                <div class="w-9 h-9 rounded-xl bg-emerald-400/20 text-emerald-300 flex items-center justify-center shrink-0">
                    <i data-lucide="headphones" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="font-bold text-white">Untuk Petugas Loket</div>
                    <div class="text-[11px] text-slate-300">1 Loket Melayani 3 Layanan</div>
                </div>
            </div>

            <div class="flex items-center gap-3 bg-white/10 rounded-2xl p-3 border border-white/10">
                <div class="w-9 h-9 rounded-xl bg-sky-400/20 text-sky-300 flex items-center justify-center shrink-0">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="font-bold text-white">Untuk Administrator</div>
                    <div class="text-[11px] text-slate-300">Master Data, Audio & Laporan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Switcher Navigation Tabs -->
    <div class="bg-white rounded-2xl p-1.5 border border-slate-200/90 shadow-xs flex flex-wrap gap-1 tab-nav no-print">
        <button type="button" onclick="switchGuideTab('pengunjung')" id="tab-btn-pengunjung"
            class="guide-tab-btn flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all cursor-pointer bg-[#00529C] text-white shadow-sm">
            <i data-lucide="user-check" class="w-4 h-4"></i>
            <span>1. Alur Pengunjung</span>
        </button>

        <button type="button" onclick="switchGuideTab('petugas')" id="tab-btn-petugas"
            class="guide-tab-btn flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all cursor-pointer bg-slate-50 text-slate-600 hover:bg-slate-100">
            <i data-lucide="headphones" class="w-4 h-4"></i>
            <span>2. Panduan Pegawai Loket</span>
        </button>

        <button type="button" onclick="switchGuideTab('admin')" id="tab-btn-admin"
            class="guide-tab-btn flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all cursor-pointer bg-slate-50 text-slate-600 hover:bg-slate-100">
            <i data-lucide="shield" class="w-4 h-4"></i>
            <span>3. Panduan Administrator</span>
        </button>

        <button type="button" onclick="switchGuideTab('keyboard')" id="tab-btn-keyboard"
            class="guide-tab-btn flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all cursor-pointer bg-slate-50 text-slate-600 hover:bg-slate-100">
            <i data-lucide="keyboard" class="w-4 h-4"></i>
            <span>4. Pintasan Keyboard</span>
        </button>

        <button type="button" onclick="switchGuideTab('faq')" id="tab-btn-faq"
            class="guide-tab-btn flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all cursor-pointer bg-slate-50 text-slate-600 hover:bg-slate-100">
            <i data-lucide="help-circle" class="w-4 h-4"></i>
            <span>5. FAQ & Kendala</span>
        </button>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: PANDUAN PENGUNJUNG                  -->
    <!-- ========================================== -->
    <div id="tab-pengunjung" class="tab-content space-y-6">
        
        <!-- Visual 5-Step Infographic Diagram -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
            <div class="text-center max-w-xl mx-auto mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-[#00529C]">Alur Pelayanan Ramah Pengunjung</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-1 uppercase font-sans">
                    5 Langkah Mudah Mengambil & Mengikuti Antrean
                </h2>
                <p class="text-xs text-slate-500 mt-1">Sistem bebas kertas (*paperless*), tidak perlu menyentuh layar jika tidak ada layar sentuh.</p>
            </div>

            <!-- Steps Grid -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 relative">
                <!-- Step 1 -->
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 flex flex-col justify-between text-center relative group hover:border-[#0093DD] transition-all">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-sky-100 text-[#00529C] font-black text-sm flex items-center justify-center mb-3">
                        1
                    </div>
                    <div>
                        <div class="text-xs font-black text-slate-900 uppercase">Scan QR / Pilih di Layar</div>
                        <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                            Buka kamera HP dan scan QR Code di meja depan (*Standee*) atau monitor.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 text-[10px] font-bold text-sky-700">
                        📱 Bebas Sentuh
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 flex flex-col justify-between text-center relative group hover:border-amber-400 transition-all">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-amber-100 text-amber-700 font-black text-sm flex items-center justify-center mb-3">
                        2
                    </div>
                    <div>
                        <div class="text-xs font-black text-slate-900 uppercase">Pilih Kategori Layanan</div>
                        <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                            Pilih Perpustakaan (A), Konsultasi Statistik (B), atau Romantik (C).
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 text-[10px] font-bold text-amber-700">
                        🎯 3 Pilihan Layanan
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 flex flex-col justify-between text-center relative group hover:border-emerald-400 transition-all">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-emerald-100 text-emerald-700 font-black text-sm flex items-center justify-center mb-3">
                        3
                    </div>
                    <div>
                        <div class="text-xs font-black text-slate-900 uppercase">Dapatkan e-Tiket</div>
                        <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                            Nomor antrean langsung muncul di layar HP dengan indikator sisa antrean realtime.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 text-[10px] font-bold text-emerald-700">
                        🎫 Live Tracking
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 flex flex-col justify-between text-center relative group hover:border-[#002f55] transition-all">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-[#002f55] text-white font-black text-sm flex items-center justify-center mb-3">
                        4
                    </div>
                    <div>
                        <div class="text-xs font-black text-slate-900 uppercase">Menuju Loket 1</div>
                        <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                            Saat nomor dipanggil lewat suara audio dan layar TV, silakan maju ke Loket 1.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 text-[10px] font-bold text-[#002f55]">
                        📢 Panggilan Audio TV
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 flex flex-col justify-between text-center relative group hover:border-purple-400 transition-all">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-purple-100 text-purple-700 font-black text-sm flex items-center justify-center mb-3">
                        5
                    </div>
                    <div>
                        <div class="text-xs font-black text-slate-900 uppercase">Survei Kepuasan (SKM)</div>
                        <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                            Setelah selesai, berikan bintang kepuasan bintang 1-5 langsung dari HP Anda.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 text-[10px] font-bold text-purple-700">
                        ⭐ Survei BPS
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Comparison: Paperless HP vs Tiket Fisik Kiosk -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Paperless Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-emerald-200 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <i data-lucide="smartphone" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 uppercase">Metode 1: Antrean via HP (Paperless)</h3>
                        <p class="text-xs text-emerald-700 font-semibold">Sangat Direkomendasikan • Ramah Lingkungan</p>
                    </div>
                </div>
                <ul class="space-y-2.5 text-xs text-slate-600">
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
                        <span>Pengunjung cukup mengarahkan kamera HP ke QR Code yang ada di <strong>Standee Meja Akrilik</strong> atau monitor.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
                        <span>Pengunjung dapat mengisi nama dan instansi (opsional) untuk mempermudah petugas melayani.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
                        <span>Status antrean otomatis berganti warna dari <strong>Kuning (Menunggu)</strong> menjadi <strong>Hijau Berkedip (Dipanggil)</strong> saat giliran tiba.</span>
                    </li>
                </ul>
            </div>

            <!-- Kiosk Monitor Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-sky-100 text-[#00529C] flex items-center justify-center">
                        <i data-lucide="monitor" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 uppercase">Metode 2: Layar Monitor / Kiosk</h3>
                        <p class="text-xs text-sky-700 font-semibold">Bisa menggunakan Mouse atau Touchscreen</p>
                    </div>
                </div>
                <ul class="space-y-2.5 text-xs text-slate-600">
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-[#00529C] shrink-0 mt-0.5"></i>
                        <span>Pengunjung yang tidak membawa HP dapat mengklik salah satu tombol layanan di layar monitor meja depan.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-[#00529C] shrink-0 mt-0.5"></i>
                        <span>Nomor antrean langsung terdaftar dan dapat dicetak ke printer struk thermal (58mm/80mm) jika printer terpasang.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-[#00529C] shrink-0 mt-0.5"></i>
                        <span>Di monitor juga terdapat QR Code yang selalu aktif di layar untuk pengunjung yang ingin beralih ke HP.</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <!-- ========================================== -->
    <!-- TAB 2: PANDUAN PEGAWAI LOKET               -->
    <!-- ========================================== -->
    <div id="tab-petugas" class="tab-content space-y-6 hidden">
        
        <!-- Konsep 1 Loket 3 Layanan -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-[#002f55] text-white flex items-center justify-center">
                    <i data-lucide="store" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-black text-[#002f55] uppercase font-sans">
                        Konsep 1 Loket Melayani 3 Layanan (Terpadu)
                    </h2>
                    <p class="text-xs text-slate-500">Mekanisme pelayanan efisien untuk ruang Pelayanan Statistik Terpadu BPS</p>
                </div>
            </div>

            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                Di BPS Kabupaten Karanganyar, seluruh pelayanan statistik dipusatkan di <strong>Loket 1</strong>. Namun sistem tetap memisahkan kode antrean agar petugas mengetahui jenis kebutuhan data pengunjung sejak awal:
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="p-4 rounded-2xl bg-sky-50 border border-sky-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-7 h-7 rounded-lg bg-[#0093DD] text-white font-black text-xs flex items-center justify-center">A</span>
                        <span class="font-bold text-xs text-slate-900">Perpustakaan</span>
                    </div>
                    <p class="text-[11px] text-slate-600">Membaca atau mengunduh publikasi & data cetak BPS.</p>
                </div>

                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-7 h-7 rounded-lg bg-[#16A34A] text-white font-black text-xs flex items-center justify-center">B</span>
                        <span class="font-bold text-xs text-slate-900">Konsultasi Statistik</span>
                    </div>
                    <p class="text-[11px] text-slate-600">Konsultasi metadata, konsep, dan metodologi statistik.</p>
                </div>

                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-7 h-7 rounded-lg bg-[#EB891B] text-white font-black text-xs flex items-center justify-center">C</span>
                        <span class="font-bold text-xs text-slate-900">Romantik</span>
                    </div>
                    <p class="text-[11px] text-slate-600">Rekomendasi Kegiatan Statistik Sektoral bagi OPD / Instansi.</p>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600">
                💡 <strong>Cara Memanggil:</strong> Pada dropdown di atas, petugas dapat memilih <strong>"Semua Layanan (A, B, C)"</strong> untuk memanggil pengunjung sesuai urutan waktu kedatangan (*First Come First Served*), atau memilih salah satu kategori jika ingin melayani konsultasi tertentu terlebih dahulu.
            </div>
        </div>

        <!-- 4 Tombol Kontrol Petugas -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wide mb-4">
                Fungsi 4 Tombol Utama di Dashboard Petugas
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Panggil Berikutnya -->
                <div class="p-5 rounded-2xl bg-sky-50 border border-sky-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-black uppercase text-[#00529C]">1. Panggil Berikutnya</span>
                            <span class="px-2 py-0.5 rounded bg-sky-200 text-sky-800 font-mono text-[10px] font-bold">Space / F2</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Memanggil antrean berikutnya dari daftar tunggu. Suara otomatis berbunyi di layar TV dan status antrean aktif.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-sky-200 text-[11px] font-bold text-sky-800">
                        ⚡ Memanggil nomor baru
                    </div>
                </div>

                <!-- Panggil Ulang -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-black uppercase text-slate-800">2. Panggil Ulang</span>
                            <span class="px-2 py-0.5 rounded bg-slate-200 text-slate-800 font-mono text-[10px] font-bold">R</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Membunyikan ulang panggilan audio jika pengunjung belum mendengar atau belum maju ke loket.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200 text-[11px] font-bold text-slate-600">
                        🔁 Maksimal disarankan 3x
                    </div>
                </div>

                <!-- Lewati -->
                <div class="p-5 rounded-2xl bg-rose-50 border border-rose-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-black uppercase text-rose-800">3. Lewati (Skip)</span>
                            <span class="px-2 py-0.5 rounded bg-rose-200 text-rose-800 font-mono text-[10px] font-bold">S</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Gunakan jika pengunjung tidak hadir setelah dipanggil berulang kali. Nomor akan ditandai *dilewati*.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-rose-200 text-[11px] font-bold text-rose-700">
                        ⏭️ Mengabaikan antrean
                    </div>
                </div>

                <!-- Selesaikan -->
                <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-black uppercase text-emerald-800">4. Selesaikan</span>
                            <span class="px-2 py-0.5 rounded bg-emerald-200 text-emerald-800 font-mono text-[10px] font-bold">Enter / C</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Menandai bahwa pelayanan selesai. Pada HP pengunjung akan langsung muncul survei kepuasan SKM.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-emerald-200 text-[11px] font-bold text-emerald-700">
                        ✅ Pelayanan tuntas
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ========================================== -->
    <!-- TAB 3: PANDUAN ADMINISTRATOR               -->
    <!-- ========================================== -->
    <div id="tab-admin" class="tab-content space-y-6 hidden">
        
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-[#00529C] text-white flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 uppercase font-sans">
                        Panduan Pengelolaan & Konfigurasi Sistem
                    </h2>
                    <p class="text-xs text-slate-500">Tugas dan tanggung jawab Pengelola IT / Administrator Sistem BPS</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- 1. Master Petugas -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex items-center gap-2 text-xs font-black text-slate-900 uppercase">
                        <i data-lucide="users" class="w-4 h-4 text-[#00529C]"></i>
                        <span>1. Pengelolaan Akun Petugas Loket</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Di menu <strong>Petugas & Pengguna</strong>, admin dapat mendaftarkan akun pegawai baru dengan role <code>Petugas</code> atau <code>Admin</code>. Petugas yang login akan otomatis tercatat namanya pada laporan setiap antrean yang mereka selesaikan.
                    </p>
                </div>

                <!-- 2. Pengaturan Audio & TV -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex items-center gap-2 text-xs font-black text-slate-900 uppercase">
                        <i data-lucide="volume-2" class="w-4 h-4 text-emerald-600"></i>
                        <span>2. Kalibrasi Suara Audio & TV</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Di menu <strong>Pengaturan Sistem</strong>, admin dapat mengaktifkan bunyi Chime nada dua-tone (Ding-Dong), mengatur volume suara, serta mengatur kecepatan (*Voice Rate*) dan tinggi nada (*Pitch*) suara wanita Bahasa Indonesia.
                    </p>
                </div>

                <!-- 3. Running Text Pengumuman -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex items-center gap-2 text-xs font-black text-slate-900 uppercase">
                        <i data-lucide="megaphone" class="w-4 h-4 text-amber-600"></i>
                        <span>3. Running Text Pengumuman TV</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Di menu <strong>Pengumuman TV</strong>, admin dapat memperbarui teks berjalan (*running text*) di bagian bawah layar TV secara langsung tanpa perlu merestart TV atau komputer.
                    </p>
                </div>

                <!-- 4. Laporan Kinerja & SKM -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex items-center gap-2 text-xs font-black text-slate-900 uppercase">
                        <i data-lucide="file-spreadsheet" class="w-4 h-4 text-purple-600"></i>
                        <span>4. Laporan Rekapitulasi & Ekspor CSV</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Di menu <strong>Riwayat & Laporan</strong>, admin dapat melihat data pengunjung, waktu tunggu rata-rata, waktu pelayanan, dan hasil penilaian kepuasan masyarakat (SKM). Data dapat diekspor ke file CSV/Excel dengan sekali klik.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <!-- ========================================== -->
    <!-- TAB 4: PINTASAN KEYBOARD (SHORTCUTS)      -->
    <!-- ========================================== -->
    <div id="tab-keyboard" class="tab-content space-y-6 hidden">
        
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
            <div class="text-center max-w-xl mx-auto mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-[#00529C]">Efisiensi Kerja Petugas Loket</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-1 uppercase font-sans">
                    Daftar Pintasan Tombol Keyboard (*Hotkeys*)
                </h2>
                <p class="text-xs text-slate-500 mt-1">Petugas dapat mengoperasikan antrean tanpa perlu menyentuh mouse!</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center hover:border-[#00529C] transition-all">
                    <kbd class="px-4 py-2 rounded-xl bg-white border-2 border-slate-300 text-slate-800 font-mono font-black text-base shadow-sm inline-block mb-3">
                        Space / F2
                    </kbd>
                    <div class="text-xs font-black text-slate-900 uppercase">Panggil Berikutnya</div>
                    <p class="text-[11px] text-slate-500 mt-1">Memanggil nomor antrean giliran selanjutnya.</p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center hover:border-sky-500 transition-all">
                    <kbd class="px-4 py-2 rounded-xl bg-white border-2 border-slate-300 text-sky-800 font-mono font-black text-base shadow-sm inline-block mb-3">
                        R
                    </kbd>
                    <div class="text-xs font-black text-slate-900 uppercase">Panggil Ulang (Recall)</div>
                    <p class="text-[11px] text-slate-500 mt-1">Membunyikan ulang suara panggilan antrean.</p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center hover:border-rose-500 transition-all">
                    <kbd class="px-4 py-2 rounded-xl bg-white border-2 border-slate-300 text-rose-800 font-mono font-black text-base shadow-sm inline-block mb-3">
                        S
                    </kbd>
                    <div class="text-xs font-black text-slate-900 uppercase">Lewati (Skip)</div>
                    <p class="text-[11px] text-slate-500 mt-1">Melewati antrean jika pengunjung tidak hadir.</p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-center hover:border-emerald-500 transition-all">
                    <kbd class="px-4 py-2 rounded-xl bg-white border-2 border-slate-300 text-emerald-800 font-mono font-black text-base shadow-sm inline-block mb-3">
                        Enter / C
                    </kbd>
                    <div class="text-xs font-black text-slate-900 uppercase">Selesaikan (Complete)</div>
                    <p class="text-[11px] text-slate-500 mt-1">Menandai pelayanan telah selesai tuntas.</p>
                </div>
            </div>

            <div class="mt-6 p-4 rounded-xl bg-sky-50 border border-sky-200 text-xs text-sky-900 flex items-center justify-between">
                <span>🖥️ <strong>Tips Layar Penuh TV:</strong> Tekan tombol <strong>F11</strong> pada browser TV untuk menampilkan layar TV antrean tanpa batas tab & taskbar.</span>
                <span class="font-bold">F11 = Fullscreen TV</span>
            </div>
        </div>

    </div>

    <!-- ========================================== -->
    <!-- TAB 5: FAQ & TROUBLESHOOTING (KENDALA)    -->
    <!-- ========================================== -->
    <div id="tab-faq" class="tab-content space-y-4 hidden">
        
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-4">
            <h2 class="text-lg font-black text-slate-900 uppercase font-sans mb-4">
                Pertanyaan yang Sering Diajukan (*Frequently Asked Questions*)
            </h2>

            <!-- FAQ 1 -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <h3 class="text-xs sm:text-sm font-black text-slate-900 flex items-center gap-2">
                    <i data-lucide="help-circle" class="w-4 h-4 text-[#00529C]"></i>
                    <span>Mengapa audio suara di layar TV tidak bersuara saat pertama kali dibuka?</span>
                </h3>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed pl-6">
                    Browser modern (Chrome/Edge/Firefox) memiliki kebijakan keamanan (*Autoplay Policy*) yang melarang web membunyikan suara otomatis sebelum ada interaksi pengguna. Cukup klik tombol <strong>"Aktifkan Suara"</strong> di header TV sekali saja saat pagi hari saat menyalakan TV.
                </p>
            </div>

            <!-- FAQ 2 -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <h3 class="text-xs sm:text-sm font-black text-slate-900 flex items-center gap-2">
                    <i data-lucide="help-circle" class="w-4 h-4 text-[#00529C]"></i>
                    <span>Bagaimana jika kantor tidak memiliki layar sentuh (*touchscreen*)?</span>
                </h3>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed pl-6">
                    Gunakan fitur <strong>Standee Meja Akrilik</strong>. Buka alamat <code>/cetak-qr</code> lalu cetak posternya ke kertas A4/A5, dan taruh di meja depan. Pengunjung dapat langsung memindai QR Code menggunakan kamera smartphone mereka.
                </p>
            </div>

            <!-- FAQ 3 -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <h3 class="text-xs sm:text-sm font-black text-slate-900 flex items-center gap-2">
                    <i data-lucide="help-circle" class="w-4 h-4 text-[#00529C]"></i>
                    <span>Apakah nomor antrean otomatis ter-reset setiap hari?</span>
                </h3>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed pl-6">
                    Ya, sistem mengelompokkan nomor antrean berdasarkan tanggal hari ini (<code>service_date</code>). Setiap hari berganti (pukul 00:00 WIB), antrean otomatis kembali dimulai dari nomor <strong>A-001, B-001, dan C-001</strong>. Data hari-hari sebelumnya tetap tersimpan rapi di menu Laporan.
                </p>
            </div>

            <!-- FAQ 4 -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <h3 class="text-xs sm:text-sm font-black text-slate-900 flex items-center gap-2">
                    <i data-lucide="help-circle" class="w-4 h-4 text-[#00529C]"></i>
                    <span>Apakah smartphone pengunjung harus terhubung ke Wi-Fi kantor BPS?</span>
                </h3>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed pl-6">
                    Jika server antrean dijalankan di komputer lokal kantor (misal: <code>http://192.168.1.16:8000</code>), smartphone pengunjung harus terhubung ke jaringan Wi-Fi kantor BPS yang sama. Jika kantor memiliki domain publik atau IP publik, antrean dapat diakses dari paket data seluler manapun.
                </p>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function switchGuideTab(tabId) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

        // Reset button states
        document.querySelectorAll('.guide-tab-btn').forEach(btn => {
            btn.className = "guide-tab-btn flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all cursor-pointer bg-slate-50 text-slate-600 hover:bg-slate-100";
        });

        // Show active tab
        const activeTab = document.getElementById('tab-' + tabId);
        if (activeTab) {
            activeTab.classList.remove('hidden');
        }

        // Highlight active button
        const activeBtn = document.getElementById('tab-btn-' + tabId);
        if (activeBtn) {
            activeBtn.className = "guide-tab-btn flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all cursor-pointer bg-[#00529C] text-white shadow-sm";
        }

        if (window.createIcons) window.createIcons();
    }
</script>
@endpush

