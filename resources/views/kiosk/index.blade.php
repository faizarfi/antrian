@extends('layouts.base')

@section('title', 'Pelayanan Statistik Terpadu - BPS Kabupaten Karanganyar')

@push('styles')
<style>
    @media print {
        body.print-thermal-active * {
            visibility: hidden !important;
        }
        body.print-thermal-active #thermal-ticket,
        body.print-thermal-active #thermal-ticket * {
            visibility: visible !important;
        }
        body.print-thermal-active #thermal-ticket {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            max-width: 78mm !important;
            margin: 0 !important;
            padding: 5mm !important;
            background: #ffffff !important;
            color: #000000 !important;
            box-shadow: none !important;
            border: none !important;
        }
        body.print-thermal-active @page {
            margin: 0;
            size: auto;
        }
    }
</style>
@endpush

@section('body')
<div class="min-h-full flex flex-col justify-between bg-slate-100 select-none" oncontextmenu="return false;" ondragstart="return false;">
    
    <!-- Official BPS Top Bar (Identik dengan header resmi bps.go.id) -->
    <div class="bg-[#002f55] text-white text-xs py-1.5 px-4 sm:px-8 border-b border-sky-900/60 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <!-- Bendera Merah Putih Kecil -->
            <div class="w-4 h-3 rounded-xs overflow-hidden flex flex-col border border-white/20 shadow-xs">
                <div class="h-1.5 bg-red-600"></div>
                <div class="h-1.5 bg-white"></div>
            </div>
            <span class="font-semibold tracking-wide text-[11px] text-slate-200">Situs Resmi Layanan BPS Kabupaten Karanganyar</span>
        </div>
        <div class="hidden sm:flex items-center gap-4 text-[11px] text-sky-200 font-medium">
            <span>Portal PST: pst.bps.go.id</span>
            <span>•</span>
            <span class="italic font-normal">"Melayani dengan Hati Sepenuh Jiwa"</span>
        </div>
    </div>

    <!-- Official BPS Navigation Bar Header -->
    <header class="bg-white border-b border-slate-200 shadow-xs py-3.5 px-4 sm:px-8">
        <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">
            <!-- Authentic BPS Logo Lockup -->
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/bps-logo.svg') }}" alt="Badan Pusat Statistik Kabupaten Karanganyar" class="h-12 sm:h-14 w-auto object-contain">
            </div>

            <!-- PST Badge & Slogan -->
            <div class="flex items-center gap-3 text-right">
                <div class="hidden sm:block">
                    <div class="text-xs font-black uppercase tracking-wider text-[#002f55]">Pelayanan Statistik Terpadu (PST)</div>
                    <div class="text-[11px] text-slate-500 font-medium">Cepat • Tepat • Akurat • Bebas Biaya (GRATIS)</div>
                </div>
                <!-- BPS Tri-Color Strip -->
                <div class="flex flex-col gap-1 w-1.5 h-9 rounded-full overflow-hidden shrink-0">
                    <div class="h-1/3 bg-[#0093DD]"></div>
                    <div class="h-1/3 bg-[#EB891B]"></div>
                    <div class="h-1/3 bg-[#68B92E]"></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area: Kiosk Instruction & 3 Cards -->
    <main class="max-w-6xl mx-auto w-full px-4 sm:px-8 py-6 my-auto">
        <!-- Title & Instruction Banner -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/90 shadow-sm mb-6 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <div class="max-w-xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-[#00529C] border border-sky-200 mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Anjungan Antrean Mandiri PST</span>
                </span>
                <h1 class="text-xl sm:text-2xl font-black text-[#003764] tracking-tight font-sans">
                    Sistem Antrean Pelayanan Statistik Terpadu
                </h1>
                <div class="text-sm font-bold text-[#0093DD] tracking-wide mt-0.5 uppercase">
                    BPS Kabupaten Karanganyar
                </div>
                <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                    Pengunjung dapat mengklik kategori layanan di bawah atau <strong>langsung scan QR Code di samping menggunakan kamera HP</strong> tanpa perlu menyentuh layar.
                </p>
                <div class="mt-3 flex items-center gap-4 text-xs">
                    <a href="{{ route('kiosk.standee') }}" class="inline-flex items-center gap-1.5 font-bold text-[#00529C] hover:text-[#003B70] transition-colors">
                        <i data-lucide="printer" class="w-4 h-4 text-[#0093DD]"></i>
                        <span>Cetak Poster Standee Meja Akrilik (A4/A5)</span>
                    </a>
                </div>
            </div>

            <!-- Always-Visible Direct QR Code Card (No Touchscreen Needed) -->
            <div class="flex items-center gap-4 bg-slate-50 border-2 border-[#00529C]/30 rounded-2xl p-4 shrink-0 shadow-xs">
                <div class="bg-white p-2 rounded-xl border border-slate-200 shrink-0 shadow-xs">
                    <canvas id="kiosk-live-qr" class="w-24 h-24 block"></canvas>
                </div>
                <div class="text-left">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wider mb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                        Bebas Layar Sentuh
                    </span>
                    <h3 class="text-sm font-black text-slate-900 leading-tight uppercase font-sans">Scan QR di HP</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5 max-w-[150px] leading-tight">
                        Arahkan kamera smartphone ke QR Code ini untuk ambil tiket.
                    </p>
                    <a href="{{ route('kiosk.standee') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-[#00529C] hover:text-[#003B70] mt-2">
                        <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                        <span>Cetak Standee Meja</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 3 Official Service Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-7">
            @foreach($services as $service)
                <button type="button" onclick="requestTicket({{ $service->id }})"
                    class="group text-left bg-white rounded-3xl p-6 sm:p-7 border-2 border-slate-200 hover:border-[#0093DD] shadow-md hover:shadow-xl hover:-translate-y-1 active:translate-y-0 active:scale-[0.99] transition-all duration-200 flex flex-col justify-between overflow-hidden cursor-pointer relative">
                    
                    <!-- Official Top Color Bar for the Census / Service -->
                    <div class="absolute top-0 left-0 right-0 h-2.5 transition-all group-hover:h-3.5" {!! 'style="background-color: ' . $service->color . '"' !!}></div>

                    <div>
                        <!-- Header: Code Badge & Waiting Count -->
                        <div class="flex items-center justify-between mb-5">
                            <span class="w-14 h-14 rounded-2xl text-white font-black text-2xl sm:text-3xl flex items-center justify-center shadow-md"
                                {!! 'style="background-color: ' . $service->color . '"' !!}>
                                {{ $service->code }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-bold text-slate-600 bg-slate-100 flex items-center gap-1.5 border border-slate-200/60">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Menunggu: <strong>{{ $waitingCounts[$service->id] ?? 0 }}</strong></span>
                            </span>
                        </div>

                        <!-- Service Title (Grounded in BPS Typography) -->
                        <h3 class="text-lg sm:text-xl font-extrabold text-[#003764] group-hover:text-[#0093DD] transition-colors mb-2.5 leading-snug">
                            {{ $service->name }}
                        </h3>

                        <!-- Description -->
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                            {{ $service->description }}
                        </p>
                    </div>

                    <!-- Touch / Click Callout -->
                    <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold group-hover:translate-x-1 transition-transform"
                        {!! 'style="color: ' . $service->color . '"' !!}>
                        <span class="tracking-wider">AMBIL TIKET DI SINI</span>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white transition-colors"
                            {!! 'style="background-color: ' . $service->color . '"' !!}>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </div>
                    </div>
                </button>
            @endforeach
        </div>
    </main>

    <!-- Official BPS Footer -->
    <footer class="bg-white border-t border-slate-200/90 py-4 px-4 sm:px-8 text-xs text-slate-600">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <div>
                <p class="font-bold text-[#003764]">Badan Pusat Statistik Kabupaten Karanganyar</p>
                <p class="text-[11px] text-slate-500">Jl. Lawu No. 385, Badran Asri, Karanganyar, Jawa Tengah 57712 • Telp: (0271) 495023</p>
            </div>
            <div class="text-[11px] text-slate-500">
                <span class="font-semibold text-emerald-700">Seluruh Pelayanan Bebas Biaya (GRATIS)</span> • <span class="text-rose-700 font-semibold">Tolak Gratifikasi!</span>
            </div>
        </div>
    </footer>
</div>

<!-- Modal Tiket Muncul Setelah Sentuh Layanan -->
<div id="ticket-modal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 sm:p-8 text-center shadow-2xl border border-slate-100 transform transition-all duration-300 scale-95 opacity-0" id="ticket-modal-card">
        
        <!-- Official Checkmark -->
        <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600">
            <i data-lucide="check" class="w-7 h-7"></i>
        </div>

        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Tiket Berhasil Diterbitkan</p>
        <h3 class="text-sm font-black text-[#003764] uppercase mt-0.5" id="modal-office-name">BPS KABUPATEN KARANGANYAR</h3>

        <!-- Thermal Ticket Simulated Box -->
        <div class="my-5 p-5 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-300 text-slate-900" id="thermal-ticket-content">
            <div class="text-[11px] uppercase tracking-wider font-semibold text-slate-500 mb-1">NOMOR ANTREAN ANDA</div>
            <div class="text-5xl font-black font-mono tracking-tight text-[#003764] py-1" id="modal-ticket-number">
                A-001
            </div>
            <div class="text-xs font-extrabold text-[#0093DD] uppercase tracking-wide mt-1" id="modal-service-name">
                PELAYANAN PERPUSTAKAAN
            </div>
            
            <div class="mt-4 pt-3 border-t border-slate-200 text-xs text-slate-600 space-y-1">
                <div class="flex justify-between">
                    <span>Tanggal:</span>
                    <span class="font-medium font-mono" id="modal-date">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Waktu:</span>
                    <span class="font-medium font-mono" id="modal-time">-</span>
                </div>
                <div class="flex justify-between text-amber-700 font-semibold" id="modal-ahead-box">
                    <span>Antrean di depan:</span>
                    <span id="modal-ahead-count">0 Orang</span>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-200 text-[11px] text-slate-500 italic leading-snug" id="modal-footer-note">
                Harap menunggu hingga nomor antrean Anda dipanggil di layar monitor.
            </div>

            <!-- QR Code Transfer to Smartphone (Paperless) -->
            <div id="modal-qr-box" class="mt-3 p-3 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center gap-3 text-left">
                <div class="p-1 bg-white rounded-lg border border-emerald-200 shrink-0 shadow-xs">
                    <canvas id="modal-ticket-qr" class="w-14 h-14 block"></canvas>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-emerald-950 flex items-center gap-1">
                        <i data-lucide="smartphone" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Bawa Tiket di HP (Bebas Kertas)</span>
                    </div>
                    <p class="text-[10px] text-emerald-700 mt-0.5 leading-tight">
                        Scan QR Code di samping untuk melihat sisa antrean langsung di HP Anda.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-2.5">
            <button type="button" onclick="printThermalTicket()"
                class="w-full py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs shadow-md flex items-center justify-center gap-2 transition-colors cursor-pointer">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Cetak Tiket Kertas Thermal (Opsional)</span>
            </button>

            <button type="button" onclick="closeTicketModal()"
                class="w-full py-2.5 px-4 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs transition-colors border border-emerald-200 cursor-pointer">
                <span>Selesai (Kembali dalam <span id="countdown-timer">{{ $autoRedirect }}</span>s)</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal QR Code Ambil Antrean di HP -->
<div id="qr-online-modal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-slate-100 relative">
        <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shadow-inner">
            <i data-lucide="qr-code" class="w-6 h-6"></i>
        </div>
        <h3 class="text-base font-extrabold text-[#003764]">Scan QR untuk Antrean Digital</h3>
        <p class="text-xs text-slate-500 mt-1 mb-4">Arahkan kamera ponsel Anda ke QR Code di bawah untuk mengambil nomor antrean mandiri tanpa kertas.</p>
        
        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 inline-block shadow-inner">
            <canvas id="qr-kiosk-canvas" class="mx-auto block"></canvas>
        </div>
        
        <div class="mt-4 p-2.5 rounded-xl bg-sky-50 border border-sky-100 text-[11px] text-[#003764] font-medium">
            URL: <strong class="font-mono text-sky-700">{{ route('ticket.online') }}</strong>
        </div>

        <button type="button" onclick="closeQrKioskModal()"
            class="w-full mt-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors cursor-pointer">
            Tutup
        </button>
    </div>
</div>

<!-- Printable Section for Thermal Paper (58mm / 80mm) -->
<div id="thermal-ticket" class="hidden">
    <div style="font-family: monospace; text-align: center; font-size: 10pt; line-height: 1.3;">
        <div style="font-weight: bold; font-size: 12pt;">BADAN PUSAT STATISTIK</div>
        <div style="font-weight: bold; font-size: 11pt;">KABUPATEN KARANGANYAR</div>
        <div style="font-size: 8pt; border-bottom: 1px dashed #000; padding-bottom: 4px; margin-bottom: 6px;">
            Jl. Lawu No. 385, Karanganyar
        </div>

        <div style="font-size: 9pt; font-weight: normal; margin-top: 4px;">NOMOR ANTREAN</div>
        <div style="font-size: 32pt; font-weight: 900; margin: 3px 0;" id="print-ticket-number">A-001</div>
        <div style="font-size: 10pt; font-weight: bold;" id="print-service-name">PELAYANAN PERPUSTAKAAN</div>

        <div style="margin: 8px 0; border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 5px 0; font-size: 8pt; text-align: left;">
            <div>Tanggal: <span id="print-date">02 November 2026</span></div>
            <div>Waktu  : <span id="print-time">09.15 WIB</span></div>
        </div>

        <div style="font-size: 8pt; margin-top: 6px; line-height: 1.2;">
            Harap menunggu hingga nomor dipanggil.<br>
            Seluruh Pelayanan BPS Bebas Biaya (GRATIS).
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="application/json" id="kiosk-config">
{!! json_encode(['autoRedirect' => (int) $autoRedirect]) !!}
</script>

<script>
    let countdownInterval = null;
    const autoRedirectSeconds = JSON.parse(document.getElementById('kiosk-config').textContent).autoRedirect;

    function playBeep() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(800, ctx.currentTime);
            gain.gain.setValueAtTime(0.2, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.2);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.2);
        } catch (e) {}
    }

    async function requestTicket(serviceId) {
        playBeep();

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch("{{ route('kiosk.take') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify({ service_id: serviceId })
            });

            const data = await response.json();
            if (data.success && data.ticket) {
                showTicketModal(data.ticket);
            } else {
                alert('Gagal mengambil tiket. Silakan coba kembali.');
            }
        } catch (error) {
            console.error('Error take ticket:', error);
            alert('Terjadi kesalahan jaringan saat mengambil tiket antrean.');
        }
    }

    function showTicketModal(ticket) {
        document.getElementById('modal-ticket-number').textContent = ticket.ticket_number;
        document.getElementById('modal-service-name').textContent = ticket.service_name.toUpperCase();
        document.getElementById('modal-date').textContent = ticket.date;
        document.getElementById('modal-time').textContent = ticket.time;
        document.getElementById('modal-ahead-count').textContent = ticket.waiting_count_ahead + ' Orang';

        document.getElementById('print-ticket-number').textContent = ticket.ticket_number;
        document.getElementById('print-service-name').textContent = ticket.service_name.toUpperCase();
        document.getElementById('print-date').textContent = ticket.date;
        document.getElementById('print-time').textContent = ticket.time;

        // Render QR Code for mobile live tracking if URL present
        if (ticket.tracking_url && window.QRCode) {
            const qrCanvas = document.getElementById('modal-ticket-qr');
            if (qrCanvas) {
                window.QRCode.toCanvas(qrCanvas, ticket.tracking_url, {
                    width: 60,
                    margin: 0,
                    color: { dark: '#00223d', light: '#ffffff' }
                });
            }
        }

        const modal = document.getElementById('ticket-modal');
        const card = document.getElementById('ticket-modal-card');
        modal.classList.remove('hidden');

        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 10);

        if (window.createIcons) window.createIcons();

        let remaining = autoRedirectSeconds;
        const timerEl = document.getElementById('countdown-timer');
        timerEl.textContent = remaining;

        clearInterval(countdownInterval);
        countdownInterval = setInterval(() => {
            remaining--;
            timerEl.textContent = remaining;
            if (remaining <= 0) {
                closeTicketModal();
            }
        }, 1000);
    }

    function printThermalTicket() {
        document.body.classList.add('print-thermal-active');
        window.print();
        setTimeout(() => {
            document.body.classList.remove('print-thermal-active');
        }, 1000);
    }

    function closeTicketModal() {
        clearInterval(countdownInterval);
        const card = document.getElementById('ticket-modal-card');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            document.getElementById('ticket-modal').classList.add('hidden');
            window.location.reload();
        }, 250);
    }

    function openQrKioskModal() {
        const modal = document.getElementById('qr-online-modal');
        modal.classList.remove('hidden');
        const canvas = document.getElementById('qr-kiosk-canvas');
        if (canvas && window.QRCode) {
            window.QRCode.toCanvas(canvas, "{{ $onlineUrl ?? route('ticket.online') }}", {
                width: 170,
                margin: 1,
                color: {
                    dark: '#00223d',
                    light: '#ffffff'
                }
            });
        }
        if (window.createIcons) window.createIcons();
    }

    function closeQrKioskModal() {
        document.getElementById('qr-online-modal').classList.add('hidden');
    }

    // Auto-render always-visible Live QR code for non-touchscreen setups
    document.addEventListener('DOMContentLoaded', function() {
        const liveCanvas = document.getElementById('kiosk-live-qr');
        if (liveCanvas && window.QRCode) {
            window.QRCode.toCanvas(liveCanvas, "{{ $onlineUrl ?? route('ticket.online') }}", {
                width: 96,
                margin: 0,
                color: {
                    dark: '#002f55',
                    light: '#ffffff'
                }
            });
        }
    });
</script>
@endpush
