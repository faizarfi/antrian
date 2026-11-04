@extends('layouts.admin')

@section('page_title', 'Pengaturan Sistem & Audio')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h3 class="text-base font-bold text-slate-900">Konfigurasi Sistem Antrean</h3>
        <p class="text-xs text-slate-500">Kelola identitas instansi, tampilan layar TV, sistem suara panggilan, dan konfigurasi cetak tiket</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Group 1: Identitas Instansi -->
        <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <i data-lucide="building-2" class="w-5 h-5 text-sky-600"></i>
                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Identitas Kantor & Layanan</h4>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="office_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Nama Satuan Kerja BPS</label>
                    <input type="text" name="office_name" id="office_name" value="{{ \App\Models\Setting::get('office_name') }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">
                </div>

                <div class="sm:col-span-2">
                    <label for="app_title" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Judul Aplikasi</label>
                    <input type="text" name="app_title" id="app_title" value="{{ \App\Models\Setting::get('app_title') }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">
                </div>

                <div class="sm:col-span-2">
                    <label for="office_address" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Alamat Kantor</label>
                    <input type="text" name="office_address" id="office_address" value="{{ \App\Models\Setting::get('office_address') }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label for="office_phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="office_phone" id="office_phone" value="{{ \App\Models\Setting::get('office_phone') }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">
                </div>
            </div>
        </div>

        <!-- Group 2: Pengaturan Layar TV Display -->
        <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <i data-lucide="tv" class="w-5 h-5 text-indigo-600"></i>
                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Tampilan Televisi (TV Display 16:9)</h4>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="running_text" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Teks Berjalan di Bawah Layar (Running Text)</label>
                    <textarea name="running_text" id="running_text" rows="3"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">{{ \App\Models\Setting::get('running_text') }}</textarea>
                    <p class="text-[11px] text-slate-500 mt-1">Gunakan bullet point (•) untuk memisahkan antar pengumuman.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="tv_welcome_title" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Judul Layar Saat Siaga (Idle)</label>
                        <input type="text" name="tv_welcome_title" id="tv_welcome_title" value="{{ \App\Models\Setting::get('tv_welcome_title') }}"
                            class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">
                    </div>
                    <div>
                        <label for="tv_welcome_subtitle" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Pesan Sambutan Siaga</label>
                        <input type="text" name="tv_welcome_subtitle" id="tv_welcome_subtitle" value="{{ \App\Models\Setting::get('tv_welcome_subtitle') }}"
                            class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Group 3: Pengaturan Suara & Panggilan (Audio SpeechSynthesis & Chime) -->
        <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="volume-2" class="w-5 h-5 text-emerald-600"></i>
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Suara Pemanggilan Otomatis (Speech Synthesis)</h4>
                </div>
                <button type="button" onclick="testAudioCall()" class="px-3 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-colors">
                    <i data-lucide="play" class="w-3.5 h-3.5"></i>
                    <span>Uji Suara Panggilan</span>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="audio_enabled" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Status Audio</label>
                    <select name="audio_enabled" id="audio_enabled"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">
                        <option value="1" {{ \App\Models\Setting::get('audio_enabled', '1') === '1' ? 'selected' : '' }}>Aktif (Suara Berbunyi Otomatis)</option>
                        <option value="0" {{ \App\Models\Setting::get('audio_enabled', '1') === '0' ? 'selected' : '' }}>Nonaktif (Hening / Tanpa Suara)</option>
                    </select>
                </div>

                <div>
                    <label for="audio_chime" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Nada Bel Pembuka (Chime)</label>
                    <select name="audio_chime" id="audio_chime"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">
                        <option value="1" {{ \App\Models\Setting::get('audio_chime', '1') === '1' ? 'selected' : '' }}>Aktif (Nada Bel Bell Ding-Dong)</option>
                        <option value="0" {{ \App\Models\Setting::get('audio_chime', '1') === '0' ? 'selected' : '' }}>Tanpa Nada Bel</option>
                    </select>
                </div>

                <div>
                    <label for="audio_voice_rate" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Kecepatan Bicara (Rate: 0.5 - 1.5)
                    </label>
                    <input type="number" step="0.05" min="0.5" max="1.5" name="audio_voice_rate" id="audio_voice_rate"
                        value="{{ \App\Models\Setting::get('audio_voice_rate', '0.9') }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 font-mono">
                    <p class="text-[11px] text-slate-500 mt-1">Disarankan: 0.85 - 0.95 agar artikulasi jelas terdengar di ruang tunggu.</p>
                </div>

                <div>
                    <label for="audio_voice_pitch" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Tinggi Nada (Pitch: 0.5 - 1.5)
                    </label>
                    <input type="number" step="0.05" min="0.5" max="1.5" name="audio_voice_pitch" id="audio_voice_pitch"
                        value="{{ \App\Models\Setting::get('audio_voice_pitch', '1.0') }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 font-mono">
                    <p class="text-[11px] text-slate-500 mt-1">Default: 1.0</p>
                </div>
            </div>
        </div>

        <!-- Group 4: Pengaturan Cetak Tiket & Kiosk -->
        <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <i data-lucide="printer" class="w-5 h-5 text-amber-600"></i>
                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Pengaturan Cetak Tiket & Kiosk Pengunjung</h4>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="ticket_auto_redirect_seconds" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Hitung Mundur Kiosk Kembali Otomatis (Detik)
                    </label>
                    <input type="number" min="3" max="30" name="ticket_auto_redirect_seconds" id="ticket_auto_redirect_seconds"
                        value="{{ \App\Models\Setting::get('ticket_auto_redirect_seconds', '6') }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 font-mono">
                </div>

                <div class="sm:col-span-2">
                    <label for="ticket_footer_note" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">
                        Pesan Kaki pada Kertas Tiket Thermal (58mm / 80mm)
                    </label>
                    <input type="text" name="ticket_footer_note" id="ticket_footer_note"
                        value="{{ \App\Models\Setting::get('ticket_footer_note', 'Harap menunggu hingga nomor antrean Anda dipanggil di layar monitor.') }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4">
            <button type="submit" class="px-6 py-2.5 bg-sky-700 hover:bg-sky-800 text-white rounded-xl text-xs font-semibold shadow-md transition-all flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Simpan Seluruh Pengaturan</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function playChimeSound() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            const now = ctx.currentTime;
            
            // First chime tone
            const osc1 = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(659.25, now); // E5
            gain1.gain.setValueAtTime(0.3, now);
            gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.start(now);
            osc1.stop(now + 0.5);

            // Second chime tone
            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(880, now + 0.25); // A5
            gain2.gain.setValueAtTime(0.3, now + 0.25);
            gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.9);
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.start(now + 0.25);
            osc2.stop(now + 0.9);
        } catch (e) {
            console.warn('Audio Context error:', e);
        }
    }

    function testAudioCall() {
        playChimeSound();
        setTimeout(() => {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const rate = parseFloat(document.getElementById('audio_voice_rate').value) || 0.9;
                const pitch = parseFloat(document.getElementById('audio_voice_pitch').value) || 1.0;

                const text = "Nomor antrean A nol nol satu, pelayanan perpustakaan, silakan menuju loket satu.";
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'id-ID';
                utterance.rate = rate;
                utterance.pitch = pitch;

                const voices = window.speechSynthesis.getVoices();
                const idVoice = voices.find(v => v.lang.startsWith('id'));
                if (idVoice) {
                    utterance.voice = idVoice;
                }

                window.speechSynthesis.speak(utterance);
            } else {
                alert('Browser ini tidak mendukung Speech Synthesis API.');
            }
        }, 900);
    }
</script>
@endpush

