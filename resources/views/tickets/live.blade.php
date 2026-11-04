@extends('layouts.base')

@section('title', 'E-Ticket ' . $ticket->ticket_number . ' - BPS Kabupaten Karanganyar')

@section('body')
<div class="min-h-full flex flex-col justify-between bg-slate-900 text-white select-none font-sans relative overflow-x-hidden">

    <!-- Top Ribbon -->
    <div class="bg-[#001726] text-slate-300 text-[11px] py-1.5 px-4 border-b border-sky-900/60 flex items-center justify-between z-10 shrink-0">
        <div class="flex items-center gap-2">
            <div class="w-3.5 h-2.5 rounded-xs overflow-hidden flex flex-col border border-white/20 shadow-xs">
                <div class="h-1.5 bg-red-600"></div>
                <div class="h-1 bg-white"></div>
            </div>
            <span class="font-bold tracking-wider uppercase text-slate-200">E-TICKET RESMI BPS</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="text-xs font-mono text-emerald-400">Live Tracker</span>
        </div>
    </div>

    <!-- Header Mobile -->
    <header class="bg-[#00223d]/90 backdrop-blur-md border-b border-white/10 py-3.5 px-4 z-10 shrink-0">
        <div class="max-w-md mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/bps-emblem-official.svg') }}" alt="Logo BPS" class="h-9 w-auto object-contain">
                <div>
                    <h1 class="text-xs font-black text-white leading-tight uppercase">BPS KABUPATEN KARANGANYAR</h1>
                    <p class="text-[10px] font-semibold text-[#38BDF8] tracking-wider uppercase">PELAYANAN STATISTIK TERPADU</p>
                </div>
            </div>
            <!-- Audio Indicator -->
            <button id="btn-sound-toggle" type="button" onclick="unlockAudio()" class="px-2.5 py-1 rounded-lg bg-white/10 border border-white/20 text-xs font-bold text-amber-300 flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="volume-2" id="icon-sound" class="w-3.5 h-3.5"></i>
                <span id="text-sound" class="text-[10px]">Audio Aktif</span>
            </button>
        </div>
    </header>

    <!-- Main Live Container -->
    <main class="max-w-md mx-auto w-full px-4 py-5 flex-1 flex flex-col justify-center relative z-10">

        <!-- Ticket Card Box -->
        <div class="bg-gradient-to-b from-slate-800 to-slate-850 rounded-3xl p-6 border-2 border-white/10 shadow-2xl relative overflow-hidden text-center transition-all duration-300" id="card-ticket-main">
            
            <!-- Top Census Color Bar -->
            <div class="absolute top-0 left-0 right-0 h-2.5" {!! 'style="background-color: ' . $ticket->service->color . '"' !!}></div>

            <!-- Service Code Badge -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider text-white shadow-xs mb-3" {!! 'style="background-color: ' . $ticket->service->color . '"' !!}>
                <span>Layanan {{ $ticket->service->code }}</span>
                <span>•</span>
                <span>{{ $ticket->service->name }}</span>
            </div>

            <!-- Giant Ticket Number -->
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">NOMOR ANTREAN ANDA</div>
            <div class="text-7xl font-black font-mono tracking-tighter text-white py-2 drop-shadow-lg" id="ticket-number-display">
                {{ $ticket->ticket_number }}
            </div>

            @if($ticket->visitor_name || $ticket->visitor_institution)
                <div class="text-xs text-slate-300 bg-white/5 py-1.5 px-3 rounded-xl border border-white/10 inline-block mb-3">
                    <span class="font-bold text-white">{{ $ticket->visitor_name ?? 'Pengunjung' }}</span>
                    @if($ticket->visitor_institution)
                        <span class="text-slate-400">• {{ $ticket->visitor_institution }}</span>
                    @endif
                </div>
            @endif

            <!-- Live Status Alert Container -->
            <div id="status-container" class="mt-2 transition-all duration-300">
                @if($ticket->status === 'waiting')
                    <div class="p-4 rounded-2xl bg-amber-500/15 border border-amber-500/40 text-amber-200">
                        <div class="flex items-center justify-center gap-2 font-black text-sm uppercase tracking-wider text-amber-300">
                            <i data-lucide="clock" class="w-4 h-4 animate-spin text-amber-400"></i>
                            <span>SEDANG MENUNGGU GILIRAN</span>
                        </div>
                        <div class="mt-2 text-2xl font-black font-mono text-white" id="waiting-count-display">
                            {{ $waitingAhead }} <span class="text-xs font-medium text-amber-300">antrean di depan Anda</span>
                        </div>
                        <p class="text-[11px] text-amber-200/80 mt-2 leading-tight">
                            Tetap buka halaman ini. Ponsel Anda akan bergetar dan berbunyi otomatis saat nomor Anda dipanggil menuju loket.
                        </p>
                    </div>
                @elseif($ticket->status === 'called')
                    <div class="p-5 rounded-2xl bg-emerald-500/25 border-2 border-emerald-400 text-white animate-bounce">
                        <div class="flex items-center justify-center gap-2 font-black text-sm uppercase tracking-wider text-emerald-300">
                            <i data-lucide="megaphone" class="w-5 h-5 text-emerald-300 animate-pulse"></i>
                            <span>NOMOR ANDA SEDANG DIPANGGIL!</span>
                        </div>
                        <div class="mt-3 py-2 px-4 rounded-xl bg-emerald-600 font-black text-xl text-white uppercase tracking-wider shadow-lg">
                            SILAKAN MENUJU {{ strtoupper($ticket->counter?->name ?? 'LOKET PELAYANAN') }}
                        </div>
                        <p class="text-xs text-emerald-200 mt-3 font-semibold">
                            Tunjukkan layar ponsel ini kepada petugas di loket.
                        </p>
                    </div>
                @elseif($ticket->status === 'completed')
                    <div class="p-4 rounded-2xl bg-sky-500/20 border border-sky-400/50 text-white">
                        <div class="flex items-center justify-center gap-2 font-black text-sm uppercase tracking-wider text-sky-300">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-sky-400"></i>
                            <span>PELAYANAN SELESAI</span>
                        </div>
                        <p class="text-xs text-slate-300 mt-2">
                            Terima kasih telah berkunjung ke Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar.
                        </p>
                    </div>
                @elseif($ticket->status === 'skipped')
                    <div class="p-4 rounded-2xl bg-rose-500/20 border border-rose-500/40 text-rose-200">
                        <div class="flex items-center justify-center gap-2 font-black text-sm uppercase tracking-wider text-rose-300">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-400"></i>
                            <span>ANTREAN DILEWATI</span>
                        </div>
                        <p class="text-xs text-rose-200 mt-2">
                            Nomor antrean ini dilewati karena Anda belum berada di loket saat pemanggilan. Silakan lapor ke petugas jika Anda sudah hadir.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Meta details -->
            <div class="mt-5 pt-4 border-t border-white/10 text-[11px] text-slate-400 flex items-center justify-between">
                <span>Tanggal: <strong class="text-slate-200">{{ $ticket->created_at->translatedFormat('d F Y') }}</strong></span>
                <span>Waktu: <strong class="text-slate-200 font-mono">{{ $ticket->created_at->format('H:i') }} WIB</strong></span>
            </div>
        </div>

        <!-- Feedback Survey Card (Shown when completed or accessible) -->
        <div id="feedback-card" class="{{ $ticket->status === 'completed' && !$ticket->feedback_rating ? '' : 'hidden' }} mt-5 bg-white rounded-3xl p-5 text-slate-900 shadow-xl border border-slate-200">
            <div class="text-center mb-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-sky-50 text-sky-800 border border-sky-200 mb-1.5">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-500"></i>
                    <span>Survei Kepuasan Masyarakat (SKM)</span>
                </span>
                <h3 class="text-sm font-extrabold text-[#003764]">Bagaimana Pelayanan Kami Hari Ini?</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Penilaian Anda sangat berharga untuk perbaikan layanan BPS Karanganyar.</p>
            </div>

            <form id="form-feedback" onsubmit="submitFeedback(event)" class="space-y-4">
                @csrf
                <div class="grid grid-cols-4 gap-2 text-center" id="rating-buttons">
                    <label class="cursor-pointer">
                        <input type="radio" name="feedback_rating" value="1" class="peer sr-only">
                        <div class="p-2.5 rounded-2xl border-2 border-slate-200 peer-checked:border-rose-500 peer-checked:bg-rose-50 transition-all">
                            <div class="text-2xl">😞</div>
                            <div class="text-[10px] font-bold text-slate-700 mt-1">Tidak Puas</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="feedback_rating" value="2" class="peer sr-only">
                        <div class="p-2.5 rounded-2xl border-2 border-slate-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 transition-all">
                            <div class="text-2xl">😐</div>
                            <div class="text-[10px] font-bold text-slate-700 mt-1">Cukup</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="feedback_rating" value="3" class="peer sr-only">
                        <div class="p-2.5 rounded-2xl border-2 border-slate-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                            <div class="text-2xl">😊</div>
                            <div class="text-[10px] font-bold text-slate-700 mt-1">Puas</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="feedback_rating" value="4" class="peer sr-only" checked>
                        <div class="p-2.5 rounded-2xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                            <div class="text-2xl">🌟</div>
                            <div class="text-[10px] font-bold text-slate-700 mt-1">Sangat Puas</div>
                        </div>
                    </label>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Kritik & Saran (Opsional):</label>
                    <textarea name="feedback_comment" rows="2" placeholder="Tuliskan pengalaman atau saran Anda untuk BPS..."
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#0093DD] text-slate-900"></textarea>
                </div>

                <button type="submit" id="btn-submit-feedback"
                    class="w-full py-3 px-4 rounded-xl bg-[#0093DD] hover:bg-[#007cb8] text-white font-extrabold text-xs shadow-md transition-colors flex items-center justify-center gap-2 cursor-pointer">
                    <i data-lucide="thumbs-up" class="w-4 h-4"></i>
                    <span>Kirim Penilaian Layanan</span>
                </button>
            </form>
        </div>

        <!-- Success feedback alert -->
        <div id="feedback-success" class="hidden mt-4 p-4 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-200 text-xs text-center font-bold">
            <i data-lucide="check-circle" class="w-5 h-5 mx-auto mb-1 text-emerald-400"></i>
            <span>Terima kasih! Penilaian kepuasan Anda telah berhasil disimpan.</span>
        </div>

        <!-- QR Code Transfer Box -->
        <div class="mt-5 p-4 rounded-2xl bg-white/5 border border-white/10 text-center">
            <p class="text-[11px] text-slate-300 font-semibold mb-2">Simpan atau Tunjukkan QR Code Tiket Ini:</p>
            <div class="flex justify-center p-3 bg-white rounded-2xl w-fit mx-auto shadow-md">
                <canvas id="ticket-qr-canvas"></canvas>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-mono break-all">{{ url()->current() }}</p>
        </div>

    </main>

    <!-- Footer Mobile -->
    <footer class="bg-[#001726] border-t border-sky-950 py-3 text-center text-[10px] text-slate-400 z-10 shrink-0">
        &copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Karanganyar • PST Bebas Biaya (GRATIS)
    </footer>
</div>
@endsection

@push('scripts')
<script type="application/json" id="live-ticket-meta">
{!! json_encode([
    'token' => $ticket->tracking_token,
    'statusUrl' => route('ticket.live.status', ['token' => $ticket->tracking_token]),
    'feedbackUrl' => route('ticket.feedback', ['token' => $ticket->tracking_token]),
    'status' => $ticket->status,
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>

<script>
    const TICKET_META = JSON.parse(document.getElementById('live-ticket-meta').textContent);
    const ticketToken = TICKET_META.token;
    const statusUrl = TICKET_META.statusUrl;
    const feedbackUrl = TICKET_META.feedbackUrl;
    
    let currentStatus = TICKET_META.status;
    let hasPlayedSound = false;
    let audioUnlocked = false;

    // Web Audio chime for phone speaker
    function playCallingChime() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            
            const now = ctx.currentTime;
            const notes = [523.25, 659.25, 783.99, 1046.50]; // C5, E5, G5, C6 (melodic major chime)
            notes.forEach((freq, i) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(freq, now + (i * 0.18));
                gain.gain.setValueAtTime(0, now + (i * 0.18));
                gain.gain.linearRampToValueAtTime(0.3, now + (i * 0.18) + 0.04);
                gain.gain.exponentialRampToValueAtTime(0.001, now + (i * 0.18) + 0.6);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now + (i * 0.18));
                osc.stop(now + (i * 0.18) + 0.6);
            });

            // Vibrate phone if mobile browser supports Vibration API
            if ('vibrate' in navigator) {
                navigator.vibrate([400, 150, 400, 150, 400]);
            }
        } catch (e) {
            console.warn('Audio chime warning:', e);
        }
    }

    function unlockAudio() {
        audioUnlocked = true;
        const icon = document.getElementById('icon-sound');
        const text = document.getElementById('text-sound');
        if (text) text.textContent = 'Audio Aktif';
        playCallingChime();
    }

    // Auto unlock audio on any touch
    document.addEventListener('touchstart', function() {
        if (!audioUnlocked) audioUnlocked = true;
    }, { once: true });
    document.addEventListener('click', function() {
        if (!audioUnlocked) audioUnlocked = true;
    }, { once: true });

    // Render QR Code on canvas
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('ticket-qr-canvas');
        if (canvas && window.QRCode) {
            window.QRCode.toCanvas(canvas, window.location.href, {
                width: 140,
                margin: 1,
                color: {
                    dark: '#00223d',
                    light: '#ffffff'
                }
            });
        }
    });

    // Real-time polling
    async function checkStatus() {
        try {
            const res = await fetch(statusUrl, {
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) return;
            const data = await res.json();

            // Status transition to 'called'
            if (data.status === 'called' && currentStatus !== 'called') {
                playCallingChime();
            }

            currentStatus = data.status;
            renderStatusUI(data);

        } catch (e) {
            console.warn('Polling error:', e);
        }
    }

    function renderStatusUI(data) {
        const container = document.getElementById('status-container');
        if (!container) return;

        if (data.status === 'waiting') {
            container.innerHTML = `
                <div class="p-4 rounded-2xl bg-amber-500/15 border border-amber-500/40 text-amber-200">
                    <div class="flex items-center justify-center gap-2 font-black text-sm uppercase tracking-wider text-amber-300">
                        <i data-lucide="clock" class="w-4 h-4 animate-spin text-amber-400"></i>
                        <span>SEDANG MENUNGGU GILIRAN</span>
                    </div>
                    <div class="mt-2 text-2xl font-black font-mono text-white" id="waiting-count-display">
                        ${data.waiting_ahead} <span class="text-xs font-medium text-amber-300">antrean di depan Anda</span>
                    </div>
                    <p class="text-[11px] text-amber-200/80 mt-2 leading-tight">
                        Tetap buka halaman ini. Ponsel Anda akan bergetar dan berbunyi otomatis saat nomor Anda dipanggil menuju loket.
                    </p>
                </div>
            `;
        } else if (data.status === 'called') {
            container.innerHTML = `
                <div class="p-5 rounded-2xl bg-emerald-500/25 border-2 border-emerald-400 text-white animate-bounce">
                    <div class="flex items-center justify-center gap-2 font-black text-sm uppercase tracking-wider text-emerald-300">
                        <i data-lucide="megaphone" class="w-5 h-5 text-emerald-300 animate-pulse"></i>
                        <span>NOMOR ANDA SEDANG DIPANGGIL!</span>
                    </div>
                    <div class="mt-3 py-2 px-4 rounded-xl bg-emerald-600 font-black text-xl text-white uppercase tracking-wider shadow-lg">
                        SILAKAN MENUJU ${data.counter_name ? data.counter_name.toUpperCase() : 'LOKET PELAYANAN'}
                    </div>
                    <p class="text-xs text-emerald-200 mt-3 font-semibold">
                        Tunjukkan layar ponsel ini kepada petugas di loket.
                    </p>
                </div>
            `;
        } else if (data.status === 'completed') {
            container.innerHTML = `
                <div class="p-4 rounded-2xl bg-sky-500/20 border border-sky-400/50 text-white">
                    <div class="flex items-center justify-center gap-2 font-black text-sm uppercase tracking-wider text-sky-300">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-sky-400"></i>
                        <span>PELAYANAN SELESAI</span>
                    </div>
                    <p class="text-xs text-slate-300 mt-2">
                        Terima kasih telah berkunjung ke Pelayanan Statistik Terpadu (PST) BPS Kabupaten Karanganyar.
                    </p>
                </div>
            `;

            // Reveal feedback card if not submitted yet
            const feedbackCard = document.getElementById('feedback-card');
            if (feedbackCard && !data.feedback_submitted) {
                feedbackCard.classList.remove('hidden');
            }
        } else if (data.status === 'skipped') {
            container.innerHTML = `
                <div class="p-4 rounded-2xl bg-rose-500/20 border border-rose-500/40 text-rose-200">
                    <div class="flex items-center justify-center gap-2 font-black text-sm uppercase tracking-wider text-rose-300">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-400"></i>
                        <span>ANTREAN DILEWATI</span>
                    </div>
                    <p class="text-xs text-rose-200 mt-2">
                        Nomor antrean ini dilewati karena Anda belum berada di loket saat pemanggilan. Silakan lapor ke petugas jika Anda sudah hadir.
                    </p>
                </div>
            `;
        }

        if (window.createIcons) {
            window.createIcons();
        }
    }

    setInterval(checkStatus, 3000);

    // Submit Feedback Survey
    async function submitFeedback(e) {
        e.preventDefault();
        const form = document.getElementById('form-feedback');
        const formData = new FormData(form);
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const btn = document.getElementById('btn-submit-feedback');
            btn.disabled = true;
            btn.innerHTML = 'Mengirimkan penilaian...';

            const response = await fetch(feedbackUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    feedback_rating: formData.get('feedback_rating'),
                    feedback_comment: formData.get('feedback_comment')
                })
            });

            if (response.ok) {
                document.getElementById('feedback-card').classList.add('hidden');
                document.getElementById('feedback-success').classList.remove('hidden');
            }
        } catch (err) {
            console.error('Submit feedback error:', err);
        }
    }
</script>
@endpush

