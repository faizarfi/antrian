 @extends('layouts.base')

@section('title', 'Cetak Standee QR Antrean - BPS Kabupaten Karanganyar')

@push('styles')
<style>
    @media print {
        body {
            background-color: #ffffff !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .no-print {
            display: none !important;
        }

        .print-container {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 auto !important;
        }

        @page {
            size: A4 portrait;
            margin: 10mm;
        }
    }
</style>
@endpush

@section('body')
<div class="min-h-screen bg-slate-100 py-6 px-4 flex flex-col items-center justify-center font-sans">

    <!-- Top Action Bar (Screen Only - Hidden when printing) -->
    <div class="w-full max-w-2xl bg-white border border-slate-200 rounded-2xl p-4 mb-6 shadow-sm flex flex-wrap items-center justify-between gap-3 no-print">
        <div class="flex items-center gap-3">
            <a href="{{ route('kiosk.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 flex items-center gap-2 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Kiosk</span>
            </a>
            <span class="text-xs text-slate-500 font-medium hidden sm:inline">• Standee Meja QR Bebas Layar Sentuh</span>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#00529C] hover:bg-[#003B70] shadow-sm flex items-center gap-2 transition-colors cursor-pointer">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Cetak Poster / Standee (A4)</span>
            </button>
        </div>
    </div>

    <!-- Printable Official BPS Standee Card -->
    <div class="w-full max-w-2xl bg-white border-2 border-slate-300 rounded-3xl p-8 sm:p-10 shadow-lg text-center print-container relative overflow-hidden">
        
        <!-- Official Government Header Strip -->
        <div class="border-b-2 border-slate-200 pb-5 mb-6">
            <div class="flex items-center justify-between gap-4">
                <img src="{{ asset('images/bps-logo.svg') }}" alt="Logo BPS" class="h-14 sm:h-16 w-auto object-contain">
                <div class="text-right">
                    <div class="text-[11px] font-extrabold uppercase tracking-widest text-[#00529C]">REPUBLIK INDONESIA</div>
                    <div class="text-sm sm:text-base font-black text-slate-900 uppercase leading-tight font-sans">
                        BADAN PUSAT STATISTIK
                    </div>
                    <div class="text-xs sm:text-sm font-bold text-[#0093DD] uppercase">
                        KABUPATEN KARANGANYAR
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Heading & Announcement -->
        <div class="mb-6">
            <span class="inline-flex items-center gap-1.5 px-4 py-1 rounded-full text-xs font-extrabold bg-sky-50 text-[#00529C] border border-sky-200 uppercase tracking-wider mb-2">
                Pelayanan Statistik Terpadu (PST)
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 uppercase tracking-tight font-sans">
                AMBIL NOMOR ANTREAN DI SINI
            </h1>
            <p class="text-sm font-medium text-slate-600 mt-1 max-w-lg mx-auto">
                Silakan scan QR Code di bawah ini menggunakan kamera HP Anda untuk mengambil nomor antrean secara mandiri tanpa menyentuh layar.
            </p>
        </div>

        <!-- Large Center QR Code Frame -->
        <div class="my-6 flex flex-col items-center justify-center">
            <div class="p-4 bg-white border-4 border-[#00529C] rounded-3xl shadow-md inline-block">
                <canvas id="standee-qr-canvas" class="w-64 h-64 sm:w-72 sm:h-72 block"></canvas>
            </div>
            
            <!-- Direct URL Display -->
            <div class="mt-4 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl inline-flex items-center gap-2 text-xs font-mono text-slate-600">
                <i data-lucide="globe" class="w-3.5 h-3.5 text-[#0093DD]"></i>
                <span id="display-target-url" class="font-bold text-slate-800">{{ $onlineUrl }}</span>
            </div>
        </div>

        <!-- 3 Simple Guidance Steps -->
        <div class="grid grid-cols-3 gap-3 sm:gap-4 my-8 border-t border-b border-slate-200 py-6 text-left">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-sky-100 text-[#00529C] font-black text-sm flex items-center justify-center shrink-0">
                    1
                </div>
                <div>
                    <div class="text-xs font-black text-slate-900 leading-tight">Buka Kamera HP</div>
                    <div class="text-[11px] text-slate-500 mt-0.5">Arahkan kamera ke QR Code di atas.</div>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 font-black text-sm flex items-center justify-center shrink-0">
                    2
                </div>
                <div>
                    <div class="text-xs font-black text-slate-900 leading-tight">Pilih Layanan</div>
                    <div class="text-[11px] text-slate-500 mt-0.5">Tentukan kebutuhan layanan statistik Anda.</div>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 font-black text-sm flex items-center justify-center shrink-0">
                    3
                </div>
                <div>
                    <div class="text-xs font-black text-slate-900 leading-tight">Dapatkan Tiket</div>
                    <div class="text-[11px] text-slate-500 mt-0.5">Pantau status antrean langsung dari HP.</div>
                </div>
            </div>
        </div>

        <!-- Available Services Summary Badge List -->
        <div class="mb-6">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Kategori Layanan Tersedia:</div>
            <div class="flex flex-wrap items-center justify-center gap-2 text-xs">
                @foreach($services as $svc)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 border border-slate-200 font-bold text-slate-800">
                        <span class="w-5 h-5 rounded text-white text-[10px] font-black flex items-center justify-center"
                            {!! 'style="background-color: ' . $svc->color . '"' !!}>
                            {{ $svc->code }}
                        </span>
                        <span>{{ $svc->name }}</span>
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Official Footer Note -->
        <div class="text-[11px] text-slate-500 pt-3 border-t border-slate-100 flex items-center justify-between">
            <span>Pelayanan Bebas Biaya (GRATIS) • Cepat, Tepat, Akurat</span>
            <span class="font-semibold text-slate-700">{{ $officeAddress }}</span>
        </div>

    </div>

    <!-- Quick URL / IP Configuration (Screen Only - for admin/staff convenience) -->
    <div class="w-full max-w-2xl mt-4 bg-white/80 border border-slate-200 rounded-2xl p-4 text-xs no-print text-slate-600">
        <div class="flex items-center justify-between mb-2">
            <span class="font-bold text-slate-800 flex items-center gap-1.5">
                <i data-lucide="settings-2" class="w-4 h-4 text-[#00529C]"></i>
                <span>Pengaturan Alamat URL / IP Wi-Fi</span>
            </span>
            <span class="text-[11px] text-slate-500">Otomatis terhubung ke Wi-Fi kantor</span>
        </div>
        <p class="text-[11px] text-slate-500 mb-3">
            QR Code di atas menggunakan alamat IP lokal komputer ini agar smartphone pengunjung dapat membukanya saat terhubung ke Wi-Fi kantor yang sama. Anda dapat menyesuaikannya bila perlu:
        </p>
        <div class="flex items-center gap-2">
            <input type="text" id="custom-url-input" value="{{ $onlineUrl }}"
                class="flex-1 px-3 py-1.5 rounded-lg border border-slate-300 font-mono text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#00529C]">
            <button type="button" onclick="applyCustomUrl()"
                class="px-3.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs transition-colors cursor-pointer">
                Terapkan & Perbarui QR
            </button>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let currentQrUrl = "{{ $onlineUrl }}";

    function renderStandeeQr(url) {
        const canvas = document.getElementById('standee-qr-canvas');
        if (canvas && window.QRCode) {
            window.QRCode.toCanvas(canvas, url, {
                width: 280,
                margin: 1,
                color: {
                    dark: '#002f55',
                    light: '#ffffff'
                }
            }, function (error) {
                if (error) console.error('QR code generation error:', error);
            });
        }
        const urlDisplay = document.getElementById('display-target-url');
        if (urlDisplay) {
            urlDisplay.textContent = url;
        }
    }

    function applyCustomUrl() {
        const input = document.getElementById('custom-url-input');
        if (input && input.value.trim() !== '') {
            currentQrUrl = input.value.trim();
            renderStandeeQr(currentQrUrl);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderStandeeQr(currentQrUrl);
    });
</script>
@endpush

