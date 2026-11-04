@extends('layouts.base')

@section('title', 'Masuk Petugas & Admin - BPS Kabupaten Karanganyar')

@section('body')
<div class="min-h-full flex flex-col justify-between bg-slate-100 select-none">
    
    <!-- Official Top Republic of Indonesia Strip -->
    <div class="bg-[#002f55] text-white text-xs py-1.5 px-4 sm:px-8 border-b border-sky-900/60 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-4 h-3 rounded-xs overflow-hidden flex flex-col border border-white/20 shadow-xs">
                <div class="h-1.5 bg-red-600"></div>
                <div class="h-1.5 bg-white"></div>
            </div>
            <span class="font-semibold tracking-wide text-[11px] text-slate-200">Badan Pusat Statistik Republik Indonesia</span>
        </div>
        <div class="text-[11px] text-sky-200 font-medium">
            <span>BPS Kabupaten Karanganyar</span>
        </div>
    </div>

    <!-- Login Container -->
    <div class="my-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-6">
            <!-- Authentic BPS Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/bps-logo.svg') }}" alt="Logo BPS Karanganyar" class="h-16 w-auto object-contain">
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-[#003764] tracking-tight">
                Portal Masuk Petugas & Admin
            </h2>
            <p class="mt-1 text-xs text-slate-600 font-medium">
                Sistem Antrean Digital Pelayanan Statistik Terpadu (PST)
            </p>
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 shadow-xl shadow-slate-200/80 rounded-3xl border border-slate-200/90 sm:px-10 relative overflow-hidden">
                <!-- BPS Tri-Color Top Accent -->
                <div class="absolute top-0 left-0 right-0 h-2 flex">
                    <div class="w-1/3 bg-[#0093DD]"></div>
                    <div class="w-1/3 bg-[#EB891B]"></div>
                    <div class="w-1/3 bg-[#68B92E]"></div>
                </div>

                @if(session('success'))
                    <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-start gap-2.5">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-start gap-2.5">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0 mt-0.5"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-2.5">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 shrink-0 mt-0.5"></i>
                        <ul class="list-disc list-inside space-y-1 font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="login" class="block text-xs font-bold uppercase tracking-wider text-[#003764] mb-1.5">
                            Username atau Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <input id="login" name="login" type="text" autocomplete="username" required value="{{ old('login') }}"
                                class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition-colors"
                                placeholder="admin atau petugas1">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-[#003764] mb-1.5">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#0093DD] focus:border-[#0093DD] transition-colors"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input id="remember" name="remember" type="checkbox"
                                class="w-4 h-4 text-[#0093DD] border-slate-300 rounded focus:ring-[#0093DD]">
                            <span class="text-slate-600">Ingat saya pada perangkat ini</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-xl shadow-md text-xs font-bold uppercase tracking-wider text-white bg-[#003764] hover:bg-[#002849] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0093DD] transition-all cursor-pointer">
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            <span>Masuk ke Sistem</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-100">
                    <div class="text-[11px] text-slate-500 space-y-1 bg-slate-50 p-3.5 rounded-xl border border-slate-200/70">
                        <p class="font-bold text-[#003764] uppercase text-[10px] tracking-wider">Kredensial Bawaan Sistem:</p>
                        <p>• Administrator: <span class="font-mono font-bold text-slate-800">admin</span> / <span class="font-mono text-slate-700">password</span></p>
                        <p>• Petugas 1: <span class="font-mono font-bold text-slate-800">petugas1</span> / <span class="font-mono text-slate-700">password</span></p>
                        <p>• Petugas 2: <span class="font-mono font-bold text-slate-800">petugas2</span> / <span class="font-mono text-slate-700">password</span></p>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('kiosk.index') }}" class="text-xs text-[#0093DD] hover:text-[#0054A6] font-semibold inline-flex items-center gap-1.5 transition-colors">
                            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                            <span>Kembali ke Halaman Ambil Tiket</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Official Footer -->
    <footer class="py-4 text-center text-xs text-slate-500 border-t border-slate-200/80 bg-white">
        <p class="font-semibold text-slate-700">Badan Pusat Statistik Kabupaten Karanganyar</p>
        <p class="text-[11px] text-slate-400">Penyedia Data Statistik Berkualitas untuk Indonesia Maju</p>
    </footer>
</div>
@endsection
