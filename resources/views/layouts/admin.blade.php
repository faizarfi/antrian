@extends('layouts.base')

@push('styles')
<style>
    @media print {
        /* Reset containers for clean multi-page document printing */
        html, body {
            height: auto !important;
            overflow: visible !important;
            background: #ffffff !important;
            color: #000000 !important;
        }

        /* Hide admin shell navigation */
        aside, header, #mobile-sidebar, .no-print {
            display: none !important;
        }

        .min-h-full, .flex-1, main {
            display: block !important;
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
            padding: 0 !important;
            margin: 0 !important;
            background: #ffffff !important;
            border: none !important;
            box-shadow: none !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        @page {
            size: A4 portrait;
            margin: 12mm 10mm;
        }
    }
</style>
@endpush

@section('body')
<div class="min-h-full flex">
    <!-- Sidebar Desktop -->
    <aside class="hidden md:flex md:w-68 md:flex-col bg-[#001f38] text-slate-300 border-r border-[#002f55] shrink-0 relative">
        <!-- BPS Tri-Color Top Accent -->
        <div class="absolute top-0 left-0 right-0 h-1.5 flex z-10">
            <div class="w-1/3 bg-[#0093DD]"></div>
            <div class="w-1/3 bg-[#EB891B]"></div>
            <div class="w-1/3 bg-[#68B92E]"></div>
        </div>

        <!-- Logo & Header -->
        <div class="p-5 border-b border-white/10 flex items-center gap-3">
            <div class="h-10 w-auto flex items-center justify-center shrink-0">
                <img src="{{ asset('images/bps-emblem-official.svg') }}" alt="BPS" class="h-10 w-auto object-contain">
            </div>
            <div>
                <h1 class="text-xs font-black text-white tracking-wider uppercase leading-tight">BPS KARANGANYAR</h1>
                <p class="text-[10px] text-[#38BDF8] font-semibold uppercase tracking-wider">PANEL ADMINISTRATOR</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto text-xs">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#0093DD] text-white shadow-md' : 'hover:bg-white/10 text-slate-300' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                <span>Ringkasan Dashboard</span>
            </a>

            <div class="pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3">
                Operasional Antrean
            </div>

            <a href="{{ route('admin.reports.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-[#0093DD] text-white shadow-md' : 'hover:bg-white/10 text-slate-300' }}">
                <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                <span>Riwayat & Laporan</span>
            </a>

            <div class="pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3">
                Master Data BPS
            </div>

            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-[#0093DD] text-white shadow-md' : 'hover:bg-white/10 text-slate-300' }}">
                <i data-lucide="users" class="w-4 h-4"></i>
                <span>Petugas & Pengguna</span>
            </a>

            <a href="{{ route('admin.services.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold transition-colors {{ request()->routeIs('admin.services.*') ? 'bg-[#0093DD] text-white shadow-md' : 'hover:bg-white/10 text-slate-300' }}">
                <i data-lucide="layers" class="w-4 h-4"></i>
                <span>Layanan Antrean</span>
            </a>

            <a href="{{ route('admin.counters.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold transition-colors {{ request()->routeIs('admin.counters.*') ? 'bg-[#0093DD] text-white shadow-md' : 'hover:bg-white/10 text-slate-300' }}">
                <i data-lucide="monitor" class="w-4 h-4"></i>
                <span>Loket Pelayanan</span>
            </a>

            <div class="pt-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3">
                Tampilan & Pengaturan
            </div>

            <a href="{{ route('admin.announcements.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold transition-colors {{ request()->routeIs('admin.announcements.*') ? 'bg-[#0093DD] text-white shadow-md' : 'hover:bg-white/10 text-slate-300' }}">
                <i data-lucide="megaphone" class="w-4 h-4"></i>
                <span>Pengumuman TV</span>
            </a>

            <a href="{{ route('admin.settings.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#0093DD] text-white shadow-md' : 'hover:bg-white/10 text-slate-300' }}">
                <i data-lucide="settings" class="w-4 h-4"></i>
                <span>Pengaturan Sistem</span>
            </a>

            <a href="{{ route('admin.guide.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold transition-colors {{ request()->routeIs('admin.guide.*') ? 'bg-[#0093DD] text-white shadow-md' : 'hover:bg-white/10 text-slate-300' }}">
                <i data-lucide="book-open" class="w-4 h-4 text-[#38BDF8]"></i>
                <span>Panduan Penggunaan</span>
            </a>

            <div class="pt-4 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3">
                Akses Langsung
            </div>

            <a href="{{ route('display.tv') }}" target="_blank"
                class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold text-amber-300 bg-amber-500/10 hover:bg-amber-500/20 transition-colors border border-amber-500/20">
                <span class="flex items-center gap-2">
                    <i data-lucide="tv" class="w-3.5 h-3.5"></i>
                    <span>Layar TV Antrean</span>
                </span>
                <i data-lucide="external-link" class="w-3 h-3"></i>
            </a>

            <a href="{{ route('kiosk.index') }}" target="_blank"
                class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold text-emerald-300 bg-emerald-500/10 hover:bg-emerald-500/20 transition-colors border border-emerald-500/20">
                <span class="flex items-center gap-2">
                    <i data-lucide="ticket" class="w-3.5 h-3.5"></i>
                    <span>Kiosk Ambil Tiket</span>
                </span>
                <i data-lucide="external-link" class="w-3 h-3"></i>
            </a>

            <a href="{{ route('kiosk.standee') }}" target="_blank"
                class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold text-cyan-300 bg-cyan-500/10 hover:bg-cyan-500/20 transition-colors border border-cyan-500/20">
                <span class="flex items-center gap-2">
                    <i data-lucide="qr-code" class="w-3.5 h-3.5"></i>
                    <span>Cetak Standee Meja QR</span>
                </span>
                <i data-lucide="printer" class="w-3 h-3"></i>
            </a>

            <a href="{{ route('operator.dashboard') }}"
                class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold text-sky-300 bg-sky-500/10 hover:bg-sky-500/20 transition-colors border border-sky-500/20">
                <span class="flex items-center gap-2">
                    <i data-lucide="headphones" class="w-3.5 h-3.5"></i>
                    <span>Dashboard Petugas</span>
                </span>
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </a>
        </nav>

        <!-- User Info & Logout -->
        <div class="p-4 border-t border-white/10 bg-[#00172b]">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-[#0093DD] text-white flex items-center justify-center font-bold text-xs shadow-xs">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-400 capitalize">{{ Auth::user()->role }} BPS</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar"
                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-white/10 transition-colors cursor-pointer">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-100">
        <!-- Top Navbar with Republic of Indonesia Banner -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-20 shadow-xs">
            <div class="px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button type="button" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100"
                        onclick="document.getElementById('mobile-sidebar').classList.toggle('hidden')">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div>
                        <h2 class="text-base font-extrabold text-[#003764] leading-tight">@yield('page_title', 'Dashboard Administrator')</h2>
                        <p class="text-xs text-slate-500 hidden sm:block">Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-bold text-[#003764]" id="live-time">{{ now()->format('H:i:s') }} WIB</div>
                        <div class="text-[11px] text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</div>
                    </div>
                    <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>
                    <a href="{{ route('operator.dashboard') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#003764] text-white hover:bg-[#002849] transition-colors flex items-center gap-1.5 shadow-xs">
                        <i data-lucide="headphones" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Panel Petugas</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Mobile Sidebar Dropdown -->
        <div id="mobile-sidebar" class="hidden md:hidden bg-[#001f38] text-slate-200 p-4 border-b border-slate-800 space-y-2 text-xs">
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-white/10">Dashboard</a>
            <a href="{{ route('admin.reports.index') }}" class="block px-3 py-2 rounded-lg hover:bg-white/10">Riwayat & Laporan</a>
            <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-lg hover:bg-white/10">Petugas & Pengguna</a>
            <a href="{{ route('admin.services.index') }}" class="block px-3 py-2 rounded-lg hover:bg-white/10">Layanan Antrean</a>
            <a href="{{ route('admin.counters.index') }}" class="block px-3 py-2 rounded-lg hover:bg-white/10">Loket</a>
            <a href="{{ route('admin.announcements.index') }}" class="block px-3 py-2 rounded-lg hover:bg-white/10">Pengumuman TV</a>
            <a href="{{ route('admin.settings.index') }}" class="block px-3 py-2 rounded-lg hover:bg-white/10">Pengaturan</a>
            <a href="{{ route('admin.guide.index') }}" class="block px-3 py-2 rounded-lg hover:bg-white/10 text-sky-300 font-bold">Panduan Penggunaan</a>
            <a href="{{ route('display.tv') }}" target="_blank" class="block px-3 py-2 text-amber-300">Layar TV (Tab Baru)</a>
            <a href="{{ route('kiosk.index') }}" target="_blank" class="block px-3 py-2 text-emerald-300">Kiosk Tiket (Tab Baru)</a>
            <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-white/10">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-rose-400 hover:bg-white/10 rounded-lg">Keluar</button>
            </form>
        </div>

        <!-- Main Body Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-start gap-3 shadow-xs">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5"></i>
                    <div class="flex-1">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold flex items-start gap-3 shadow-xs">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5"></i>
                    <div class="flex-1">{{ session('warning') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-start gap-3 shadow-xs">
                    <i data-lucide="alert-octagon" class="w-5 h-5 text-rose-600 shrink-0 mt-0.5"></i>
                    <div class="flex-1">{{ session('error') }}</div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    setInterval(() => {
        const timeEl = document.getElementById('live-time');
        if (timeEl) {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            timeEl.textContent = `${hours}:${minutes}:${seconds} WIB`;
        }
    }, 1000);
</script>
@endpush
