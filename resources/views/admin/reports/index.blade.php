@extends('layouts.admin')

@section('page_title', 'Riwayat & Rekapitulasi Antrean')

@push('styles')
<style>
    @media print {
        .no-print, form, nav, .pagination, aside, header {
            display: none !important;
        }
        body, main {
            background: #ffffff !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        table {
            font-size: 10px !important;
            width: 100% !important;
            border-collapse: collapse !important;
        }
        th, td {
            padding: 4px 6px !important;
            border: 1px solid #cbd5e1 !important;
        }
        th {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }
        tr {
            page-break-inside: avoid !important;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Official Print Header (Visible ONLY when printing / saving to PDF) -->
    <div class="hidden print:block border-b-2 border-slate-900 pb-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/bps-logo.svg') }}" alt="Logo BPS" class="h-14 w-auto object-contain">
                <div>
                    <h1 class="text-base font-black uppercase text-slate-900 leading-tight">BADAN PUSAT STATISTIK KABUPATEN KARANGANYAR</h1>
                    <div class="text-xs font-bold text-[#00529C]">PELAYANAN STATISTIK TERPADU (PST)</div>
                    <div class="text-[11px] text-slate-600">Laporan Rekapitulasi Data & Kinerja Pelayanan Antrean</div>
                </div>
            </div>
            <div class="text-right text-xs text-slate-600 font-mono">
                <div>Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong></div>
                <div>Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
            </div>
        </div>
    </div>

    <!-- Filter Card & Export Button (Hidden on Print) -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm no-print">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="block w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-xs focus:ring-2 focus:ring-sky-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="block w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-xs focus:ring-2 focus:ring-sky-500 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Jenis Layanan</label>
                    <select name="service_id"
                        class="block w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-xs focus:ring-2 focus:ring-sky-500">
                        <option value="">Semua Layanan</option>
                        @foreach($services as $s)
                            <option value="{{ $s->id }}" {{ $serviceId == $s->id ? 'selected' : '' }}>
                                [{{ $s->code }}] {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Status Pelayanan</label>
                    <select name="status"
                        class="block w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-xs focus:ring-2 focus:ring-sky-500">
                        <option value="">Semua Status</option>
                        <option value="waiting" {{ $status == 'waiting' ? 'selected' : '' }}>Menunggu</option>
                        <option value="called" {{ $status == 'called' ? 'selected' : '' }}>Sedang Dipanggil</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="skipped" {{ $status == 'skipped' ? 'selected' : '' }}>Dilewati</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Petugas / Operator</label>
                    <select name="user_id"
                        class="block w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-xs focus:ring-2 focus:ring-sky-500">
                        <option value="">Semua Petugas</option>
                        @foreach($officers as $officer)
                            <option value="{{ $officer->id }}" {{ $userId == $officer->id ? 'selected' : '' }}>
                                {{ $officer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100">
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 bg-sky-700 hover:bg-sky-800 text-white rounded-xl text-xs font-semibold flex items-center gap-2 transition-colors cursor-pointer">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        <span>Terapkan Filter</span>
                    </button>
                    <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold transition-colors">
                        Reset
                    </a>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Print to PDF / Paper Button -->
                    <button type="button" onclick="window.print()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold flex items-center gap-2 shadow-sm transition-colors cursor-pointer">
                        <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                        <span>Cetak Laporan (PDF / Print)</span>
                    </button>

                    <!-- Export CSV Button with preserved query string -->
                    <a href="{{ route('admin.reports.export', request()->query()) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold flex items-center gap-2 shadow-sm transition-colors">
                        <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5"></i>
                        <span>Ekspor Excel / CSV</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Stats Bar of Filtered Results -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-semibold uppercase text-slate-500">Total Tiket</span>
            <div class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-emerald-100 bg-emerald-50/20 shadow-sm">
            <span class="text-[11px] font-semibold uppercase text-emerald-700">Tuntas Selesai</span>
            <div class="text-xl font-extrabold text-emerald-900 mt-0.5">{{ $stats['completed'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-rose-100 bg-rose-50/20 shadow-sm">
            <span class="text-[11px] font-semibold uppercase text-rose-700">Dilewati / Batal</span>
            <div class="text-xl font-extrabold text-rose-900 mt-0.5">{{ $stats['skipped'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-amber-100 bg-amber-50/20 shadow-sm">
            <span class="text-[11px] font-semibold uppercase text-amber-700">Dalam Antrean</span>
            <div class="text-xl font-extrabold text-amber-900 mt-0.5">{{ $stats['waiting'] + $stats['called'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-indigo-100 bg-indigo-50/20 shadow-sm">
            <span class="text-[11px] font-semibold uppercase text-indigo-700">Rerata Tunggu</span>
            <div class="text-xl font-extrabold text-indigo-900 mt-0.5">{{ $stats['avg_wait'] }} <span class="text-xs font-normal">menit</span></div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold tracking-wider">
                    <tr>
                        <th class="px-5 py-3">No. Antrean</th>
                        <th class="px-5 py-3">Pengunjung</th>
                        <th class="px-5 py-3">Layanan</th>
                        <th class="px-5 py-3">Tanggal & Waktu</th>
                        <th class="px-5 py-3">Loket</th>
                        <th class="px-5 py-3">Petugas</th>
                        <th class="px-5 py-3 text-center">Panggilan</th>
                        <th class="px-5 py-3">Waktu Tunggu</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Kepuasan (SKM)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-3 font-mono font-bold text-slate-900 text-sm">
                                {{ $ticket->ticket_number }}
                            </td>
                            <td class="px-5 py-3 text-xs">
                                @if($ticket->visitor_name)
                                    <div class="font-bold text-slate-900">{{ $ticket->visitor_name }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $ticket->visitor_institution ?? 'Umum' }}</div>
                                @else
                                    <span class="text-slate-400 italic">Kiosk Mandiri</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <span class="font-semibold text-slate-800">{{ $ticket->service->name ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                <div>{{ $ticket->service_date->format('d/m/Y') }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $ticket->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-5 py-3 text-slate-600 font-medium">
                                {{ $ticket->counter ? $ticket->counter->name : '-' }}
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                {{ $ticket->user ? $ticket->user->name : '-' }}
                            </td>
                            <td class="px-5 py-3 text-center font-mono">
                                {{ $ticket->call_count }}x
                            </td>
                            <td class="px-5 py-3 text-slate-600 font-mono">
                                @if($ticket->wait_duration_in_minutes !== null)
                                    {{ $ticket->wait_duration_in_minutes }} mnt
                                @else
                                    -
                                @endif
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
                            <td class="px-5 py-3 text-xs">
                                @if($ticket->feedback_rating)
                                    <span class="inline-flex items-center gap-1 font-bold {{ $ticket->feedback_rating >= 3 ? 'text-emerald-700' : 'text-amber-700' }}">
                                        @if($ticket->feedback_rating == 4) 🌟 Sangat Puas
                                        @elseif($ticket->feedback_rating == 3) 😊 Puas
                                        @elseif($ticket->feedback_rating == 2) 😐 Cukup
                                        @else 😞 Tidak Puas
                                        @endif
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-12 text-center text-slate-400">
                                Tidak ada data antrean yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="p-4 border-t border-slate-100 no-print">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
