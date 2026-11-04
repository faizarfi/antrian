@extends('layouts.base')

@section('title', 'Dashboard Petugas Loket - BPS Kabupaten Karanganyar')

@section('body')
<div class="min-h-full flex flex-col bg-slate-100 text-slate-800">

    <!-- Official Top Republic of Indonesia Strip -->
    <div class="bg-[#002f55] text-white text-xs py-1.5 px-4 sm:px-8 border-b border-sky-950/60 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-2.5">
            <div class="w-4 h-3 rounded-xs overflow-hidden flex flex-col border border-white/20 shadow-xs">
                <div class="h-1.5 bg-red-600"></div>
                <div class="h-1.5 bg-white"></div>
            </div>
            <span class="font-bold tracking-wider text-[11px] text-slate-200 uppercase">REPUBLIK INDONESIA • BADAN PUSAT STATISTIK</span>
        </div>
        <div class="flex items-center gap-4 text-[11px] text-sky-200 font-medium">
            <span class="font-bold text-white">Pelayanan Statistik Terpadu (PST)</span>
            <span>•</span>
            <span class="text-slate-200">BPS Kabupaten Karanganyar</span>
        </div>
    </div>

    <!-- Top Operator Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-20 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-4">
            <!-- Left: Authentic BPS Logo & Workspace Title -->
            <div class="flex items-center gap-4 shrink-0">
                <img src="{{ asset('images/bps-logo.svg') }}" alt="Logo BPS Karanganyar" class="h-11 w-auto object-contain">
                <div class="h-7 w-px bg-slate-200 hidden sm:block"></div>
                <div class="hidden sm:block">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Pelayanan Statistik Terpadu</div>
                    <div class="text-sm font-black text-[#002f55] uppercase tracking-tight mt-1">Dashboard Petugas Loket</div>
                </div>
            </div>

            <!-- Center: Loket 1 Badge & Filter Layanan -->
            <div class="flex items-center bg-slate-100 border border-slate-200 rounded-xl p-1 shadow-xs shrink-0">
                <!-- 1 Loket Terpadu Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 bg-[#002f55] text-white rounded-lg text-xs font-black shrink-0 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Loket 1</span>
                    <span class="text-[10px] text-sky-300 font-medium hidden md:inline">• Pelayanan Terpadu</span>
                </div>
                <input type="hidden" id="select-counter" value="{{ $selectedCounter?->id ?? 1 }}">

                <div class="h-5 w-px bg-slate-300 mx-2"></div>

                <!-- Filter Layanan -->
                <div class="flex items-center gap-2 pr-1">
                    <label for="select-service" class="text-xs font-bold text-slate-600 shrink-0">Layanan:</label>
                    <select id="select-service" onchange="updateOperatorPreferences()"
                        class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-xs font-bold text-[#00529C] focus:ring-2 focus:ring-[#00529C] focus:outline-none cursor-pointer">
                        <option value="">Semua Layanan (A, B, C)</option>
                        @foreach($services as $svc)
                            <option value="{{ $svc->id }}" {{ $selectedServiceId == $svc->id ? 'selected' : '' }}>
                                [{{ $svc->code }}] {{ $svc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Right: Operator Info, Admin Link, Layar TV, Logout -->
            <div class="flex items-center gap-2.5 shrink-0">
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl">
                    <div class="w-6 h-6 rounded-full bg-[#00529C] text-white flex items-center justify-center font-bold text-xs shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="text-left text-xs leading-tight">
                        <div class="font-bold text-slate-800">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-slate-500 capitalize">{{ Auth::user()->role }} PST</div>
                    </div>
                </div>

                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" title="Panel Admin"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 transition-colors flex items-center gap-1.5">
                        <i data-lucide="shield" class="w-3.5 h-3.5 text-[#00529C]"></i>
                        <span class="hidden sm:inline">Admin</span>
                    </a>
                @endif

                <a href="{{ route('panduan') }}" target="_blank" title="Buku Panduan & SOP Petugas"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 transition-colors flex items-center gap-1.5">
                    <i data-lucide="book-open" class="w-3.5 h-3.5 text-[#00529C]"></i>
                    <span class="hidden lg:inline">Panduan</span>
                </a>

                <a href="{{ route('display.tv') }}" target="_blank" title="Buka Layar TV Antrean"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold bg-[#00529C] hover:bg-[#003B70] text-white transition-colors flex items-center gap-1.5 shadow-xs">
                    <i data-lucide="tv" class="w-3.5 h-3.5"></i>
                    <span class="hidden sm:inline">Layar TV</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="inline-flex">
                    @csrf
                    <button type="submit" title="Keluar / Logout"
                        class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition-colors cursor-pointer">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Workspace -->
    <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Stats Bar Today -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Tiket Hari Ini</span>
                <div class="text-3xl font-black font-mono text-slate-900 mt-2" id="stat-total">{{ $stats['total'] }}</div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-amber-200 bg-amber-50/20 shadow-xs flex flex-col justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Sedang Menunggu</span>
                <div class="text-3xl font-black font-mono text-amber-700 mt-2" id="stat-waiting">{{ $stats['waiting'] }}</div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-sky-200 bg-sky-50/20 shadow-xs flex flex-col justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-sky-700">Sedang Dipanggil</span>
                <div class="text-3xl font-black font-mono text-sky-700 mt-2" id="stat-called">{{ $stats['called'] }}</div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-emerald-200 bg-emerald-50/20 shadow-xs flex flex-col justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Selesai Dilayani</span>
                <div class="text-3xl font-black font-mono text-emerald-700 mt-2" id="stat-completed">{{ $stats['completed'] }}</div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-rose-200 bg-rose-50/20 shadow-xs flex flex-col justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-700">Dilewati</span>
                <div class="text-3xl font-black font-mono text-rose-700 mt-2" id="stat-skipped">{{ $stats['skipped'] }}</div>
            </div>
        </div>

        <!-- Center Stage: Active Calling Box & Action Buttons -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Active Serving Panel (7 cols) -->
            <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/90 shadow-md p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full {{ $currentTicket ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }}"></span>
                            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-700">
                                Antrean di <span class="text-sky-700 font-extrabold" id="label-active-counter">{{ $selectedCounter?->name }}</span>
                            </h2>
                        </div>
                        <span id="badge-call-count" class="px-3 py-1 rounded-full text-xs font-bold {{ $currentTicket ? 'bg-sky-100 text-sky-800' : 'bg-slate-100 text-slate-400' }}">
                            {{ $currentTicket ? ('Dipanggil ' . $currentTicket->call_count . 'x') : 'Siaga' }}
                        </span>
                    </div>

                    <!-- Current Ticket Display -->
                    <div class="text-center py-6">
                        <div class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">
                            Nomor Antrean Sedang Dilayani
                        </div>
                        <div class="text-6xl sm:text-7xl font-black font-mono tracking-tight text-slate-900 py-2" id="current-ticket-number">
                            {{ $currentTicket ? $currentTicket->ticket_number : '-' }}
                        </div>
                        <div class="text-base sm:text-lg font-bold text-sky-800 mt-1 uppercase" id="current-service-name">
                            {{ $currentTicket ? $currentTicket->service->name : 'Belum Ada Antrean Aktif' }}
                        </div>
                        <div class="text-xs text-slate-700 font-semibold mt-1" id="current-visitor-info">
                            @if($currentTicket && $currentTicket->visitor_name)
                                <span class="text-slate-900 font-bold">{{ $currentTicket->visitor_name }}</span>
                                @if($currentTicket->visitor_institution)
                                    <span class="text-slate-500">({{ $currentTicket->visitor_institution }})</span>
                                @endif
                            @endif
                        </div>
                        <div class="text-xs text-slate-500 mt-1.5 font-mono" id="current-called-at">
                            {{ $currentTicket ? ('Waktu panggil: ' . $currentTicket->called_at->format('H:i:s') . ' WIB') : 'Tekan tombol di bawah untuk memanggil pengunjung' }}
                        </div>
                    </div>
                </div>

                <!-- Action Control Buttons -->
                <div class="space-y-3 pt-6 border-t border-slate-100">
                    <!-- Primary Call Next Button -->
                    <button type="button" onclick="callNextTicket()" id="btn-call-next"
                        class="w-full py-4 px-6 rounded-2xl bg-sky-700 hover:bg-sky-800 active:scale-[0.99] text-white font-extrabold text-base shadow-lg shadow-sky-700/25 flex items-center justify-between transition-all cursor-pointer">
                        <div class="flex items-center gap-3">
                            <i data-lucide="volume-2" class="w-6 h-6"></i>
                            <span>PANGGIL ANTREAN BERIKUTNYA</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-white/20 text-xs font-mono font-bold tracking-wider hidden sm:inline-block">Space / F2</span>
                    </button>

                    <!-- Secondary Controls: Recall, Skip, Complete -->
                    <div class="grid grid-cols-3 gap-3">
                        <button type="button" onclick="recallTicket()" id="btn-recall"
                            {{ !$currentTicket ? 'disabled' : '' }}
                            class="py-3 px-3 rounded-xl border-2 border-sky-600 bg-sky-50 hover:bg-sky-100 disabled:opacity-40 disabled:pointer-events-none text-sky-800 font-bold text-xs flex flex-col sm:flex-row items-center justify-center gap-1.5 transition-colors cursor-pointer">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="rotate-cw" class="w-4 h-4"></i>
                                <span>Panggil Ulang</span>
                            </div>
                            <span class="px-1.5 py-0.5 rounded bg-sky-200/80 text-[10px] font-mono font-bold hidden sm:inline-block">R</span>
                        </button>

                        <button type="button" onclick="skipTicket()" id="btn-skip"
                            {{ !$currentTicket ? 'disabled' : '' }}
                            class="py-3 px-3 rounded-xl border border-rose-300 bg-rose-50 hover:bg-rose-100 disabled:opacity-40 disabled:pointer-events-none text-rose-700 font-bold text-xs flex flex-col sm:flex-row items-center justify-center gap-1.5 transition-colors cursor-pointer">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="user-x" class="w-4 h-4"></i>
                                <span>Lewati</span>
                            </div>
                            <span class="px-1.5 py-0.5 rounded bg-rose-200/80 text-[10px] font-mono font-bold hidden sm:inline-block">S</span>
                        </button>

                        <button type="button" onclick="completeTicket()" id="btn-complete"
                            {{ !$currentTicket ? 'disabled' : '' }}
                            class="py-3 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] disabled:opacity-40 disabled:pointer-events-none text-white font-bold text-xs flex flex-col sm:flex-row items-center justify-center gap-1.5 transition-colors shadow-sm cursor-pointer">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                <span>Selesaikan</span>
                            </div>
                            <span class="px-1.5 py-0.5 rounded bg-emerald-800 text-[10px] font-mono font-bold hidden sm:inline-block">Enter / C</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 3 Services Waiting Queues Breakdown (5 cols) -->
            <div class="lg:col-span-5 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                        Antrean Berdasarkan Layanan
                    </h3>
                    <span class="text-xs text-slate-500 font-mono">Realtime</span>
                </div>

                <div class="space-y-4">
                    @foreach($serviceQueues as $sq)
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 relative overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1.5" {!! 'style="background-color: ' . $sq['service']->color . '"' !!}></div>

                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-8 h-8 rounded-lg text-white font-extrabold text-sm flex items-center justify-center shadow-sm"
                                        {!! 'style="background-color: ' . $sq['service']->color . '"' !!}>
                                        {{ $sq['service']->code }}
                                    </span>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900 leading-tight">{{ $sq['service']->name }}</h4>
                                        <span class="text-[11px] text-slate-500">Menunggu: <strong class="text-slate-800">{{ $sq['waiting_count'] }}</strong></span>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <span class="text-[10px] uppercase font-semibold text-slate-400">Terakhir</span>
                                    <div class="text-sm font-mono font-bold text-slate-800">
                                        {{ $sq['last_called'] ? $sq['last_called']->ticket_number : '-' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Waiting numbers chips -->
                            <div class="flex flex-wrap gap-1.5 mt-2 pt-2 border-t border-slate-100">
                                @forelse($sq['waiting_list'] as $wt)
                                    <span class="px-2 py-0.5 rounded-md font-mono text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $wt->ticket_number }}
                                    </span>
                                @empty
                                    <span class="text-[11px] text-slate-400 italic">Tidak ada antrean menunggu</span>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Bottom: Today's History for This Counter -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                    Riwayat Antrean yang Dilayani di Loket Ini Hari Ini
                </h3>
                <span class="text-xs text-slate-400">Diurutkan dari yang terbaru</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-semibold tracking-wider">
                        <tr>
                            <th class="px-5 py-3">No. Antrean</th>
                            <th class="px-5 py-3">Layanan</th>
                            <th class="px-5 py-3">Waktu Dipanggil</th>
                            <th class="px-5 py-3">Waktu Selesai</th>
                            <th class="px-5 py-3 text-center">Panggilan</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white" id="operator-history-body">
                        @forelse($recentHistories as $hist)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-3 font-mono font-bold text-slate-900 text-sm">
                                    {{ $hist->ticket_number }}
                                </td>
                                <td class="px-5 py-3 text-slate-700 font-medium">
                                    {{ $hist->service->name ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-slate-500 font-mono">
                                    {{ $hist->called_at ? $hist->called_at->format('H:i:s') : '-' }}
                                </td>
                                <td class="px-5 py-3 text-slate-500 font-mono">
                                    {{ $hist->completed_at ? $hist->completed_at->format('H:i:s') : '-' }}
                                </td>
                                <td class="px-5 py-3 text-center font-mono font-semibold">
                                    {{ $hist->call_count }}x
                                </td>
                                <td class="px-5 py-3">
                                    @if($hist->status === 'called')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-sky-50 text-sky-700 border border-sky-200">Sedang Dipanggil</span>
                                    @elseif($hist->status === 'completed')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Selesai</span>
                                    @elseif($hist->status === 'skipped')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Dilewati</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                                    Belum ada antrean yang dipanggil di loket ini hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>
@endsection

@push('scripts')
<script type="application/json" id="operator-ticket-init">
{!! json_encode(['currentTicketId' => $currentTicket?->id]) !!}
</script>

<script>
    let currentTicketId = JSON.parse(document.getElementById('operator-ticket-init').textContent).currentTicketId;

    function getSelectedCounterId() {
        return document.getElementById('select-counter').value;
    }

    function getSelectedServiceId() {
        return document.getElementById('select-service').value;
    }

    // Save preferences in session
    async function updateOperatorPreferences() {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        await fetch("{{ route('operator.preferences') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                counter_id: getSelectedCounterId(),
                service_id: getSelectedServiceId()
            })
        });
        window.location.reload();
    }

    // Call Next Ticket
    async function callNextTicket() {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const btn = document.getElementById('btn-call-next');
        btn.disabled = true;

        try {
            const response = await fetch("{{ route('operator.call-next') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    counter_id: getSelectedCounterId(),
                    service_id: getSelectedServiceId()
                })
            });

            const data = await response.json();
            if (data.success && data.ticket) {
                currentTicketId = data.ticket.id;
                document.getElementById('current-ticket-number').textContent = data.ticket.ticket_number;
                document.getElementById('current-service-name').textContent = data.ticket.service_name;
                const vInfo = document.getElementById('current-visitor-info');
                if (vInfo) {
                    vInfo.textContent = data.ticket.visitor_name ? (data.ticket.visitor_name + (data.ticket.visitor_institution ? ' (' + data.ticket.visitor_institution + ')' : '')) : '';
                }
                document.getElementById('current-called-at').textContent = 'Waktu panggil: ' + data.ticket.called_at + ' WIB';
                document.getElementById('badge-call-count').textContent = 'Dipanggil ' + data.ticket.call_count + 'x';

                document.getElementById('btn-recall').disabled = false;
                document.getElementById('btn-skip').disabled = false;
                document.getElementById('btn-complete').disabled = false;
            } else {
                alert(data.message || 'Tidak ada antrean yang menunggu.');
            }
        } catch (e) {
            console.error('Call next error:', e);
            alert('Terjadi kesalahan saat memanggil antrean.');
        } finally {
            btn.disabled = false;
            refreshDashboardData();
        }
    }

    // Recall Active Ticket
    async function recallTicket() {
        if (!currentTicketId) return;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch(`/operator/recall/${currentTicketId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    counter_id: getSelectedCounterId()
                })
            });

            const data = await response.json();
            if (data.success && data.ticket) {
                document.getElementById('badge-call-count').textContent = 'Dipanggil ' + data.ticket.call_count + 'x';
            }
        } catch (e) {
            console.error('Recall error:', e);
        }
    }

    // Complete Ticket
    async function completeTicket() {
        if (!currentTicketId) return;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch(`/operator/complete/${currentTicketId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({})
            });

            const data = await response.json();
            if (data.success) {
                resetActiveDisplay();
                refreshDashboardData();
            }
        } catch (e) {
            console.error('Complete error:', e);
        }
    }

    // Skip Ticket
    async function skipTicket() {
        if (!currentTicketId) return;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch(`/operator/skip/${currentTicketId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({})
            });

            const data = await response.json();
            if (data.success) {
                resetActiveDisplay();
                refreshDashboardData();
            }
        } catch (e) {
            console.error('Skip error:', e);
        }
    }

    function resetActiveDisplay() {
        currentTicketId = null;
        document.getElementById('current-ticket-number').textContent = '-';
        document.getElementById('current-service-name').textContent = 'Belum Ada Antrean Aktif';
        const vInfo = document.getElementById('current-visitor-info');
        if (vInfo) vInfo.textContent = '';
        document.getElementById('current-called-at').textContent = 'Silakan panggil antrean berikutnya';
        document.getElementById('badge-call-count').textContent = 'Siaga';

        document.getElementById('btn-recall').disabled = true;
        document.getElementById('btn-skip').disabled = true;
        document.getElementById('btn-complete').disabled = true;
    }

    // Keyboard Shortcuts for Operator Ergonomics
    document.addEventListener('keydown', function(e) {
        if (['INPUT', 'SELECT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
            return;
        }

        if (e.code === 'Space' || e.key === 'F2') {
            e.preventDefault();
            callNextTicket();
        } else if (e.key === 'r' || e.key === 'R') {
            e.preventDefault();
            recallTicket();
        } else if (e.key === 's' || e.key === 'S') {
            e.preventDefault();
            skipTicket();
        } else if (e.key === 'c' || e.key === 'C' || e.key === 'Enter') {
            e.preventDefault();
            completeTicket();
        }
    });

    // Periodic dashboard stats polling
    async function refreshDashboardData() {
        try {
            const response = await fetch(`/operator/refresh?counter_id=${getSelectedCounterId()}`);
            if (response.ok) {
                const data = await response.json();
                if (data.snapshot && data.snapshot.totals) {
                    const t = data.snapshot.totals;
                    document.getElementById('stat-total').textContent = t.total;
                    document.getElementById('stat-waiting').textContent = t.waiting;
                    document.getElementById('stat-called').textContent = t.called;
                    document.getElementById('stat-completed').textContent = t.completed;
                    document.getElementById('stat-skipped').textContent = t.skipped;
                }
            }
        } catch (e) {}
    }
    setInterval(refreshDashboardData, 4000);
</script>
@endpush

