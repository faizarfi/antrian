@extends('layouts.base')

@section('title', 'Layar Antrean PST - BPS Kabupaten Karanganyar')

@push('styles')
<style>
    /* Full HD 1920x1080 Landscape TV Display Styles */
    html, body {
        height: 100%;
        overflow: hidden;
        background-color: #f8fafc;
    }

    /* Continuous smooth ticker marquee */
    @keyframes marquee {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }

    .marquee-content {
        display: inline-block;
        white-space: nowrap;
        animation: marquee 32s linear infinite;
    }

    .marquee-content:hover {
        animation-play-state: paused;
    }
</style>
@endpush

@section('body')
<div class="h-screen w-screen flex flex-col justify-between bg-slate-50 text-slate-800 select-none relative font-sans">

    <!-- Official Top Republic of Indonesia Ribbon -->
    <div class="bg-[#002f55] text-white text-xs py-1.5 px-8 flex items-center justify-between z-10 shrink-0">
        <div class="flex items-center gap-2.5">
            <!-- Bendera Merah Putih -->
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

    <!-- Clean & Prestigious White Header Bar -->
    <header class="bg-white border-b border-slate-200 px-8 py-3.5 flex items-center justify-between z-10 shrink-0 shadow-xs">
        <!-- Left: Official BPS Logo Lockup -->
        <div class="flex items-center gap-5">
            <img src="{{ asset('images/bps-logo.svg') }}" alt="Logo BPS" class="h-12 w-auto object-contain">
            <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>
            <div>
                <h1 class="text-base font-black tracking-tight text-[#002f55] uppercase leading-tight font-sans">BADAN PUSAT STATISTIK KABUPATEN KARANGANYAR</h1>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pelayanan Statistik Terpadu (PST) • Layar Antrean</div>
            </div>
        </div>

        <!-- Right: Audio Button, Fullscreen & High-Contrast Digital Clock -->
        <div class="flex items-center gap-5">
            <!-- Audio Activation Button -->
            <button id="btn-audio-toggle" type="button" onclick="enableAudio()"
                class="px-3.5 py-2 rounded-lg text-xs font-semibold transition-colors flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 cursor-pointer">
                <i data-lucide="volume-x" id="audio-icon" class="w-4 h-4 text-slate-500"></i>
                <span id="audio-status-text">Aktifkan Suara</span>
            </button>

            <!-- Fullscreen Button -->
            <button type="button" onclick="toggleFullScreen()" title="Layar Penuh TV (F11)"
                class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 border border-slate-300 transition-colors cursor-pointer">
                <i data-lucide="maximize" class="w-4 h-4"></i>
            </button>

            <!-- Live Digital Clock -->
            <div class="text-right border-l border-slate-200 pl-5">
                <div class="text-2xl font-black font-mono tracking-tight text-slate-900 leading-tight" id="tv-clock">
                    {{ now()->format('H:i:s') }} <span class="text-xs text-[#00529C] font-bold">WIB</span>
                </div>
                <div class="text-xs font-medium text-slate-500 mt-0.5" id="tv-date">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>
    </header>

    <!-- Main TV Body (16:9 Landscape Composition) -->
    <main class="flex-1 p-6 grid grid-cols-12 gap-6 min-h-0 overflow-hidden">
        
        <!-- Left Hero Display: Active Called Ticket (7 cols) -->
        <div class="col-span-7 flex flex-col justify-between">
            <div class="h-full bg-white border-2 border-slate-200/90 rounded-2xl p-8 flex flex-col justify-between shadow-xs transition-all duration-300" id="call-card-container">
                
                <!-- Call Card Top Header -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-bold tracking-widest text-slate-500 uppercase" id="call-status-label">
                            NOMOR ANTREAN DIPANGGIL
                        </span>
                    </div>
                    <span id="call-badge-type" class="px-3 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-600 uppercase tracking-wider">
                        Loket Pelayanan Aktif
                    </span>
                </div>

                <!-- Center: Giant Ticket Number & Clear Destination -->
                <div class="my-auto py-4 text-center" id="ticket-number-wrapper">
                    <!-- Giant Ticket Code -->
                    <div class="text-8xl lg:text-9xl xl:text-[11rem] font-black font-mono tracking-tight text-[#002f55] leading-none py-1" id="display-ticket-number">
                        {{ $initialSnapshot['last_called']['ticket_number'] ?? 'PST' }}
                    </div>

                    <!-- Service Name -->
                    <div class="mt-4 text-2xl lg:text-3xl xl:text-4xl font-bold text-slate-700 tracking-wide uppercase" id="display-service-name">
                        {{ $initialSnapshot['last_called']['service_name'] ?? $welcomeTitle }}
                    </div>

                    <!-- Counter Instruction Box -->
                    <div class="mt-8 inline-flex items-center justify-center gap-3.5 px-10 py-4 rounded-xl bg-[#00529C] text-white shadow-sm">
                        <i data-lucide="arrow-right-circle" class="w-8 h-8 shrink-0 text-white"></i>
                        <span class="text-2xl xl:text-3xl font-extrabold uppercase tracking-wider" id="display-counter-name">
                            {{ $initialSnapshot['last_called'] ? ('SILAKAN MENUJU ' . strtoupper($initialSnapshot['last_called']['counter_name'])) : 'BPS KABUPATEN KARANGANYAR' }}
                        </span>
                    </div>
                </div>

                <!-- Card Bottom Meta -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs lg:text-sm text-slate-500">
                    <div class="flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-[#00529C]"></i>
                        <span id="display-footer-hint" class="font-medium">Tunjukkan nomor antrean Anda kepada petugas loket yang bertugas.</span>
                    </div>
                    <div class="font-mono text-slate-600 font-bold" id="display-call-time">
                        {{ $initialSnapshot['last_called']['called_at'] ?? now()->format('H:i') }} WIB
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: 3 Services Overview & Recent Calls (5 cols) -->
        <div class="col-span-5 flex flex-col justify-between gap-5">

            <!-- 3 Services Overview Cards -->
            <div class="space-y-2.5">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center justify-between px-1">
                    <span>STATUS PELAYANAN HARI INI</span>
                    <span class="text-[11px] text-slate-400 font-medium">BPS Karanganyar</span>
                </div>

                <div class="space-y-2.5" id="services-cards-container">
                    @foreach($initialSnapshot['services'] as $svc)
                        <div class="bg-white border border-slate-200/90 rounded-xl p-4 flex items-center justify-between shadow-xs hover:border-slate-300 transition-colors" id="service-card-{{ $svc['id'] }}">
                            <div class="flex items-center gap-3.5">
                                <span class="w-12 h-12 rounded-lg text-white font-black text-xl flex items-center justify-center shrink-0"
                                    {!! 'style="background-color: ' . $svc['color'] . '"' !!}>
                                    {{ $svc['code'] }}
                                </span>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 leading-tight">{{ $svc['name'] }}</h3>
                                    <div class="flex items-center gap-3 mt-1.5 text-xs text-slate-500 font-medium">
                                        <span>Menunggu: <strong class="text-slate-800 font-bold" id="svc-wait-{{ $svc['id'] }}">{{ $svc['waiting_count'] }}</strong></span>
                                        <span>•</span>
                                        <span>Total: <span class="text-slate-600 font-mono" id="svc-total-{{ $svc['id'] }}">{{ $svc['total_today'] }}</span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right pl-2 shrink-0">
                                <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Sedang Dilayani</div>
                                <div class="text-2xl font-black font-mono text-[#00529C]" id="svc-current-{{ $svc['id'] }}">
                                    {{ $svc['current_ticket'] }}
                                </div>
                                <div class="text-xs font-semibold text-slate-600" id="svc-counter-{{ $svc['id'] }}">
                                    {{ $svc['current_counter'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Called History List -->
            <div class="bg-white border border-slate-200/90 rounded-xl p-4 flex-1 flex flex-col justify-between overflow-hidden shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5 mb-2.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                        <i data-lucide="history" class="w-4 h-4 text-slate-500"></i>
                        <span>Panggilan Terakhir</span>
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">5 Antrean Terakhir</span>
                </div>

                <div class="space-y-2 overflow-y-auto flex-1 pr-1" id="recent-calls-list">
                    @forelse($initialSnapshot['recent_calls'] as $rc)
                        <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2.5">
                                <span class="px-2 py-0.5 rounded font-mono font-bold text-xs bg-slate-200 text-slate-800">
                                    {{ $rc['ticket_number'] }}
                                </span>
                                <span class="font-medium text-slate-800 truncate max-w-[170px]">{{ $rc['service_name'] }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-right">
                                <span class="font-bold text-[#00529C]">{{ $rc['counter_name'] }}</span>
                                <span class="font-mono text-slate-400 text-[11px] font-medium">{{ $rc['called_at'] }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-slate-400 font-medium" id="empty-history-text">
                            Belum ada riwayat panggilan antrean hari ini.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </main>

    <!-- Official BPS Footer Running Text / Marquee -->
    <footer class="h-12 bg-[#002f55] flex items-center overflow-hidden z-10 shrink-0">
        <div class="bg-[#00529C] h-full px-5 flex items-center gap-2 text-white font-bold text-xs uppercase tracking-wider shrink-0 z-10">
            <i data-lucide="bell" class="w-4 h-4"></i>
            <span>PENGUMUMAN</span>
        </div>
        <div class="flex-1 overflow-hidden relative">
            <div class="marquee-content text-sm font-medium text-slate-100 tracking-wide">
                {{ $runningText }}
            </div>
        </div>
        <!-- QR Code Paperless Pill on TV Footer -->
        <div class="h-full px-4 bg-[#00223d] border-l border-sky-900/60 flex items-center gap-2.5 text-xs shrink-0 z-10">
            <div class="p-1 bg-white rounded shrink-0">
                <canvas id="tv-qr-canvas" class="w-8 h-8 block"></canvas>
            </div>
            <div class="text-[11px] leading-tight hidden lg:block text-left">
                <div class="font-bold text-white uppercase tracking-wider flex items-center gap-1">
                    <i data-lucide="smartphone" class="w-3.5 h-3.5 text-sky-300"></i>
                    <span>Antrean di HP</span>
                </div>
                <div class="text-[9px] text-sky-200">Scan QR Bebas Kertas</div>
            </div>
        </div>
    </footer>

</div>
@endsection

@push('scripts')
<script type="application/json" id="tv-app-config">
{!! json_encode([
    'audioEnabled' => $audioEnabled,
    'audioChime' => $audioChime,
    'audioVoiceRate' => $audioVoiceRate,
    'audioVoicePitch' => $audioVoicePitch,
    'audioVolume' => $audioVolume,
    'welcomeTitle' => $welcomeTitle,
    'welcomeSubtitle' => $welcomeSubtitle,
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>

<script>
    // Configuration & State
    const CONFIG = JSON.parse(document.getElementById('tv-app-config').textContent);

    let audioContextUnlocked = false;
    let lastSpokenTicketId = null;
    let lastSpokenCallCount = 0;

    // Digital Clock
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const clockEl = document.getElementById('tv-clock');
        if (clockEl) {
            clockEl.innerHTML = `${hours}:${minutes}:${seconds} <span class="text-xs text-[#00529C] font-bold">WIB</span>`;
        }
    }
    setInterval(updateClock, 1000);

    // Audio Unlocker for Browser Autoplay Policy
    function enableAudio() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (AudioCtx) {
                const ctx = new AudioCtx();
                ctx.resume();
            }
            audioContextUnlocked = true;

            const btn = document.getElementById('btn-audio-toggle');
            const icon = document.getElementById('audio-icon');
            const statusText = document.getElementById('audio-status-text');

            btn.className = "px-3.5 py-2 rounded-lg text-xs font-semibold transition-colors flex items-center gap-2 bg-emerald-50 text-emerald-700 border border-emerald-300";
            statusText.textContent = "Suara Aktif";
            icon.setAttribute('data-lucide', 'volume-2');
            icon.className = "w-4 h-4 text-emerald-600";
            if (window.createIcons) window.createIcons();

            speakSentence("Sistem suara antrean Badan Pusat Statistik aktif.");
        } catch (e) {
            console.error('Audio unlock failed:', e);
        }
    }

    // Melodic Two-Tone Chime Sound Synthesis (Ding-Dong)
    function playChime() {
        if (!CONFIG.audioChime || !audioContextUnlocked) return Promise.resolve();

        return new Promise((resolve) => {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return resolve();
                const ctx = new AudioCtx();
                const now = ctx.currentTime;

                // Tone 1: E5 (659.25 Hz)
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(659.25, now);
                gain1.gain.setValueAtTime(0.35 * CONFIG.audioVolume, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.6);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.6);

                // Tone 2: A5 (880 Hz)
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(880, now + 0.3);
                gain2.gain.setValueAtTime(0.35 * CONFIG.audioVolume, now + 0.3);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 1.1);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(now + 0.3);
                osc2.stop(now + 1.1);

                setTimeout(resolve, 950);
            } catch (e) {
                console.warn('Chime error:', e);
                resolve();
            }
        });
    }

    // Indonesian Speech Synthesis Voice
    function speakSentence(text) {
        if (!CONFIG.audioEnabled || !('speechSynthesis' in window)) return;

        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'id-ID';
        utterance.rate = CONFIG.audioVoiceRate;
        utterance.pitch = CONFIG.audioVoicePitch;
        utterance.volume = CONFIG.audioVolume;

        const voices = window.speechSynthesis.getVoices();
        const idVoice = voices.find(v => v.lang.startsWith('id'));
        if (idVoice) {
            utterance.voice = idVoice;
        }

        window.speechSynthesis.speak(utterance);
    }

    async function processQueueCall(ticketData) {
        if (!ticketData || !ticketData.spoken_text) return;

        const card = document.getElementById('call-card-container');
        if (card) {
            card.classList.add('border-[#00529C]');
            setTimeout(() => card.classList.remove('border-[#00529C]'), 4000);
        }

        if (audioContextUnlocked) {
            await playChime();
            speakSentence(ticketData.spoken_text);
        }
    }

    // Update TV UI from data snapshot
    function updateDisplayUI(data) {
        if (!data) return;

        if (data.last_called) {
            const lc = data.last_called;
            document.getElementById('display-ticket-number').textContent = lc.ticket_number;
            document.getElementById('display-service-name').textContent = lc.service_name;
            document.getElementById('display-counter-name').textContent = 'SILAKAN MENUJU ' + lc.counter_name.toUpperCase();
            document.getElementById('display-call-time').textContent = (lc.called_at || '') + ' WIB';
            document.getElementById('call-status-label').textContent = 'NOMOR ANTREAN DIPANGGIL';

            const isNewCall = (lastSpokenTicketId !== lc.id) || (lastSpokenCallCount !== lc.call_count);
            if (isNewCall) {
                lastSpokenTicketId = lc.id;
                lastSpokenCallCount = lc.call_count;
                processQueueCall(lc);
            }
        }

        if (data.services && Array.isArray(data.services)) {
            data.services.forEach(svc => {
                const waitEl = document.getElementById(`svc-wait-${svc.id}`);
                const totalEl = document.getElementById(`svc-total-${svc.id}`);
                const curEl = document.getElementById(`svc-current-${svc.id}`);
                const cntEl = document.getElementById(`svc-counter-${svc.id}`);

                if (waitEl) waitEl.textContent = svc.waiting_count;
                if (totalEl) totalEl.textContent = svc.total_today;
                if (curEl) curEl.textContent = svc.current_ticket;
                if (cntEl) cntEl.textContent = svc.current_counter;
            });
        }

        if (data.recent_calls && Array.isArray(data.recent_calls)) {
            const listEl = document.getElementById('recent-calls-list');
            if (listEl && data.recent_calls.length > 0) {
                listEl.innerHTML = data.recent_calls.map(rc => `
                    <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <span class="px-2 py-0.5 rounded font-mono font-bold text-xs bg-slate-200 text-slate-800">
                                ${rc.ticket_number}
                            </span>
                            <span class="font-medium text-slate-800 truncate max-w-[170px]">${rc.service_name}</span>
                        </div>
                        <div class="flex items-center gap-3 text-right">
                            <span class="font-bold text-[#00529C]">${rc.counter_name}</span>
                            <span class="font-mono text-slate-400 text-[11px] font-medium">${rc.called_at}</span>
                        </div>
                    </div>
                `).join('');
            }
        }
    }

    async function fetchLatestData() {
        try {
            const response = await fetch("{{ route('display.data') }}");
            if (response.ok) {
                const data = await response.json();
                updateDisplayUI(data);
            }
        } catch (e) {
            console.warn('Fetch data error:', e);
        }
    }
    setInterval(fetchLatestData, 3000);

    function toggleFullScreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                alert(`Error: ${err.message}`);
            });
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }

    document.addEventListener('click', function() {
        if (!audioContextUnlocked) {
            enableAudio();
        }
    }, { once: true });

    if ('speechSynthesis' in window) {
        window.speechSynthesis.onvoiceschanged = () => {
            window.speechSynthesis.getVoices();
        };
    }

    // Render TV QR Code for mobile queue link
    document.addEventListener('DOMContentLoaded', function() {
        const tvCanvas = document.getElementById('tv-qr-canvas');
        if (tvCanvas && window.QRCode) {
            window.QRCode.toCanvas(tvCanvas, "{{ $onlineUrl ?? route('ticket.online') }}", {
                width: 32,
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
