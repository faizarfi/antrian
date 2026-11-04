@extends('layouts.base')

@section('title', 'Ambil Antrean Online Paperless - BPS Kabupaten Karanganyar')

@section('body')
<div class="min-h-full flex flex-col justify-between bg-slate-100 select-none font-sans">

    <!-- Top Ribbon -->
    <div class="bg-[#002f55] text-white text-[11px] py-1.5 px-4 border-b border-sky-900/60 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-3.5 h-2.5 rounded-xs overflow-hidden flex flex-col border border-white/20 shadow-xs">
                <div class="h-1.5 bg-red-600"></div>
                <div class="h-1 bg-white"></div>
            </div>
            <span class="font-bold tracking-wider text-slate-200 uppercase">REPUBLIK INDONESIA</span>
        </div>
        <span class="text-sky-200 font-medium">BPS Kab. Karanganyar</span>
    </div>

    <!-- Header Mobile -->
    <header class="bg-white border-b border-slate-200 py-3.5 px-4 shadow-xs">
        <div class="max-w-md mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/bps-emblem-official.svg') }}" alt="Logo BPS" class="h-10 w-auto object-contain">
                <div>
                    <h1 class="text-xs font-black text-[#003764] leading-tight uppercase">BPS KABUPATEN KARANGANYAR</h1>
                    <p class="text-[10px] font-bold text-[#0093DD] tracking-wide">PELAYANAN STATISTIK TERPADU</p>
                </div>
            </div>
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" title="Sistem Aktif Online"></div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-md mx-auto w-full px-4 py-5 flex-1 flex flex-col justify-center">

        <!-- Green Paperless Info Card -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white p-4 rounded-2xl shadow-md mb-5 relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-white/20 rounded-xl shrink-0 mt-0.5">
                    <i data-lucide="smartphone" class="w-5 h-5 text-emerald-100"></i>
                </div>
                <div>
                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider bg-white/20 text-white px-2 py-0.5 rounded-md mb-1">
                        <i data-lucide="sparkles" class="w-3 h-3 text-amber-300"></i>
                        <span>Eco-Friendly • Paperless</span>
                    </span>
                    <h2 class="text-sm font-extrabold leading-snug">Tiket Antrean Digital Mandiri</h2>
                    <p class="text-[11px] text-emerald-100 mt-0.5 leading-tight">
                        Tanpa perlu cetak kertas. Pantau giliran antrean Anda langsung dari layar ponsel.
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Ambil Antrean -->
        <form action="{{ route('ticket.online.store') }}" method="POST" id="form-take-online" class="space-y-4">
            @csrf

            <!-- Pilih Layanan -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Pilih Layanan Pelayanan:
                </label>
                <div class="space-y-3">
                    @foreach($services as $service)
                        <label class="block cursor-pointer">
                            <input type="radio" name="service_id" value="{{ $service->id }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                            <div class="p-4 bg-white rounded-2xl border-2 border-slate-200 peer-checked:border-[#0093DD] peer-checked:bg-sky-50/40 peer-checked:shadow-md transition-all relative overflow-hidden">
                                <div class="absolute top-0 left-0 bottom-0 w-2" {!! 'style="background-color: ' . $service->color . '"' !!}></div>
                                <div class="pl-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="w-7 h-7 rounded-lg flex items-center justify-center font-mono font-black text-xs text-white" {!! 'style="background-color: ' . $service->color . '"' !!}>
                                                {{ $service->code }}
                                            </span>
                                            <span class="font-extrabold text-xs text-slate-800 uppercase">{{ $service->name }}</span>
                                        </div>
                                        <span class="text-[11px] font-bold text-slate-500">
                                            Menunggu: <span class="font-mono text-amber-600">{{ $waitingCounts[$service->id] ?? 0 }}</span>
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-2 line-clamp-2 leading-snug">
                                        {{ $service->description }}
                                    </p>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('service_id')
                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Identitas Pengunjung (Opsional - Buku Tamu Digital) -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 space-y-3 shadow-xs">
                <div class="flex items-center gap-2 text-xs font-bold text-[#003764] border-b border-slate-100 pb-2">
                    <i data-lucide="clipboard-list" class="w-4 h-4 text-[#0093DD]"></i>
                    <span>Buku Tamu Digital (Opsional)</span>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Pengunjung:</label>
                    <input type="text" name="visitor_name" placeholder="Contoh: Budi Santoso"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#0093DD] text-slate-900">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Asal Instansi / OPD / Kampus / Umum:</label>
                    <input type="text" name="visitor_institution" placeholder="Contoh: Bappeda Karanganyar / Umum"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#0093DD] text-slate-900">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="btn-submit"
                class="w-full py-3.5 px-4 rounded-2xl bg-[#003764] hover:bg-[#002849] active:scale-[0.99] text-white font-extrabold text-sm shadow-lg shadow-sky-950/20 flex items-center justify-center gap-2 transition-all cursor-pointer">
                <i data-lucide="ticket" class="w-5 h-5 text-amber-300"></i>
                <span>Ambil Tiket Antrean Sekarang</span>
            </button>
        </form>

        <!-- Help Info -->
        <div class="mt-5 text-center text-xs text-slate-500">
            <p>Seluruh pelayanan statistik diberikan secara <strong>GRATIS (Bebas Biaya)</strong>.</p>
            <p class="text-[11px] text-slate-400 mt-1">BPS Kabupaten Karanganyar • Jl. Lawu No. 385</p>
        </div>
    </main>

    <!-- Footer Mobile -->
    <footer class="bg-white border-t border-slate-200 py-3 text-center text-[11px] text-slate-500">
        &copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Karanganyar
    </footer>
</div>
@endsection

