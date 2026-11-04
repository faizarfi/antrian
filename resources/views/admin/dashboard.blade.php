@extends('layouts.admin')

@section('page_title', 'Ringkasan Eksekutif & Statistik Antrean')

@section('content')
<div class="space-y-6">
    <!-- Top Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Total Today -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Total Tiket</span>
                <i data-lucide="ticket" class="w-4 h-4 text-sky-600"></i>
            </div>
            <div class="text-2xl font-extrabold text-slate-900">{{ $totalToday }}</div>
            <p class="text-[11px] text-slate-500 mt-1">Hari ini</p>
        </div>

        <!-- Waiting -->
        <div class="bg-white p-4 rounded-2xl border border-amber-200/80 shadow-sm bg-gradient-to-br from-white to-amber-50/30">
            <div class="flex items-center justify-between text-amber-700 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Menunggu</span>
                <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
            </div>
            <div class="text-2xl font-extrabold text-amber-900">{{ $waitingToday }}</div>
            <p class="text-[11px] text-amber-700 mt-1">Belum dipanggil</p>
        </div>

        <!-- Called -->
        <div class="bg-white p-4 rounded-2xl border border-sky-200/80 shadow-sm bg-gradient-to-br from-white to-sky-50/30">
            <div class="flex items-center justify-between text-sky-700 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Dipanggil</span>
                <i data-lucide="volume-2" class="w-4 h-4 text-sky-600"></i>
            </div>
            <div class="text-2xl font-extrabold text-sky-900">{{ $calledToday }}</div>
            <p class="text-[11px] text-sky-700 mt-1">Sedang dilayani</p>
        </div>

        <!-- Completed -->
        <div class="bg-white p-4 rounded-2xl border border-emerald-200/80 shadow-sm bg-gradient-to-br from-white to-emerald-50/30">
            <div class="flex items-center justify-between text-emerald-700 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Selesai</span>
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            </div>
            <div class="text-2xl font-extrabold text-emerald-900">{{ $completedToday }}</div>
            <p class="text-[11px] text-emerald-700 mt-1">Telah tuntas</p>
        </div>

        <!-- Skipped -->
        <div class="bg-white p-4 rounded-2xl border border-rose-200/80 shadow-sm bg-gradient-to-br from-white to-rose-50/30">
            <div class="flex items-center justify-between text-rose-700 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Dilewati</span>
                <i data-lucide="user-x" class="w-4 h-4 text-rose-600"></i>
            </div>
            <div class="text-2xl font-extrabold text-rose-900">{{ $skippedToday }}</div>
            <p class="text-[11px] text-rose-700 mt-1">Tidak hadir</p>
        </div>

        <!-- Average Wait -->
        <div class="bg-white p-4 rounded-2xl border border-indigo-200/80 shadow-sm bg-gradient-to-br from-white to-indigo-50/30">
            <div class="flex items-center justify-between text-indigo-700 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Rerata Tunggu</span>
                <i data-lucide="timer" class="w-4 h-4 text-indigo-600"></i>
            </div>
            <div class="text-2xl font-extrabold text-indigo-900">{{ $avgWaitMinutes }} <span class="text-xs font-semibold">mnt</span></div>
            <p class="text-[11px] text-indigo-700 mt-1">Responsif</p>
        </div>
    </div>

    <!-- Middle Section: Service Breakdown & Operational Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Service Distribution (2 cols) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Distribusi Pelayanan Hari Ini</h3>
                    <p class="text-xs text-slate-500">Jumlah pemohon berdasarkan 3 jenis layanan statistik</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg">
                    Total: {{ $totalToday }} Pengunjung
                </span>
            </div>

            <div class="space-y-4">
                @foreach($serviceStats as $stat)
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-md text-white flex items-center justify-center font-bold text-xs" {!! 'style="background-color: ' . $stat['color'] . '"' !!}>
                                    {{ $stat['code'] }}
                                </span>
                                <span class="text-sm font-bold text-slate-800">{{ $stat['name'] }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-base font-extrabold text-slate-900">{{ $stat['count'] }}</span>
                                <span class="text-xs text-slate-500">({{ $stat['percentage'] }}%)</span>
                            </div>
                        </div>
                        <!-- Progress bar -->
                        <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full transition-all duration-500"
                                {!! 'style="width: ' . $stat['percentage'] . '%; background-color: ' . $stat['color'] . '"' !!}></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Operational Status & Fast Actions -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-1">Status Operasional</h3>
                <p class="text-xs text-slate-500 mb-5">Kondisi fasilitas pelayanan terpadu</p>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-700">
                            <i data-lucide="monitor" class="w-4 h-4 text-sky-600"></i>
                            <span>Loket Aktif</span>
                        </div>
                        <span class="text-xs font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">
                            {{ $activeCounters }} Loket
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-700">
                            <i data-lucide="user-check" class="w-4 h-4 text-indigo-600"></i>
                            <span>Petugas Terdaftar</span>
                        </div>
                        <span class="text-xs font-bold px-2 py-0.5 rounded bg-sky-100 text-sky-800">
                            {{ $totalPetugas }} Petugas
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-700">
                            <i data-lucide="calendar" class="w-4 h-4 text-emerald-600"></i>
                            <span>Akumulasi Minggu Ini</span>
                        </div>
                        <span class="text-xs font-bold text-slate-800">{{ $totalWeek }} Tiket</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-700">
                            <i data-lucide="calendar-days" class="w-4 h-4 text-purple-600"></i>
                            <span>Akumulasi Bulan Ini</span>
                        </div>
                        <span class="text-xs font-bold text-slate-800">{{ $totalMonth }} Tiket</span>
                    </div>
                </div>
            </div>

            <div class="pt-5 border-t border-slate-100 mt-5">
                <a href="{{ route('admin.reports.index') }}" class="w-full flex justify-center items-center gap-2 py-2.5 px-4 bg-sky-50 text-sky-700 hover:bg-sky-100 rounded-xl text-xs font-semibold transition-colors">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    <span>Buka Riwayat & Ekspor Data Lengkap</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Recent 10 Tickets Today -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Aktivitas Antrean Terakhir Hari Ini</h3>
                <p class="text-xs text-slate-500">10 tiket antrean terbaru yang diproses oleh petugas</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-800">
                Lihat Semua Antrean &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold tracking-wider">
                    <tr>
                        <th class="px-5 py-3">No. Antrean</th>
                        <th class="px-5 py-3">Layanan</th>
                        <th class="px-5 py-3">Loket</th>
                        <th class="px-5 py-3">Petugas</th>
                        <th class="px-5 py-3">Waktu Ambil</th>
                        <th class="px-5 py-3">Waktu Panggil</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($recentTickets as $ticket)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3 font-mono font-bold text-slate-900 text-sm">
                                {{ $ticket->ticket_number }}
                            </td>
                            <td class="px-5 py-3 text-slate-700 font-medium">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full" {!! 'style="background-color: ' . ($ticket->service->color ?? '#64748b') . '"' !!}></span>
                                    <span>{{ $ticket->service->name ?? '-' }}</span>
                                </span>
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                {{ $ticket->counter ? $ticket->counter->name : '-' }}
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                {{ $ticket->user ? $ticket->user->name : '-' }}
                            </td>
                            <td class="px-5 py-3 text-slate-500 font-mono">
                                {{ $ticket->created_at->format('H:i') }}
                            </td>
                            <td class="px-5 py-3 text-slate-500 font-mono">
                                {{ $ticket->called_at ? $ticket->called_at->format('H:i') : '-' }}
                            </td>
                            <td class="px-5 py-3">
                                @if($ticket->status === 'waiting')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Menunggu</span>
                                @elseif($ticket->status === 'called')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-sky-50 text-sky-700 border border-sky-200">Dipanggil</span>
                                @elseif($ticket->status === 'completed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Selesai</span>
                                @elseif($ticket->status === 'skipped')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Dilewati</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-400">
                                Belum ada aktivitas tiket antrean untuk hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

