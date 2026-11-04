@extends('layouts.admin')

@section('page_title', 'Manajemen Layanan Antrean')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-slate-900">Kategori Pelayanan BPS</h3>
            <p class="text-xs text-slate-500">Kelola 3 layanan utama PST: Perpustakaan (A), Konsultasi Statistik (B), dan Romantik (C)</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-700 hover:bg-sky-800 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Tambah Layanan</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($services as $service)
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-5 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1.5" {!! 'style="background-color: ' . $service->color . '"' !!}></div>
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="w-10 h-10 rounded-xl text-white font-extrabold text-base flex items-center justify-center shadow-sm" {!! 'style="background-color: ' . $service->color . '"' !!}>
                            {{ $service->code }}
                        </span>
                        @if($service->is_active)
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-500">Nonaktif</span>
                        @endif
                    </div>

                    <h4 class="text-base font-bold text-slate-900 mb-1.5">{{ $service->name }}</h4>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">{{ $service->description }}</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="font-mono">Urutan: #{{ $service->order }}</span>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.services.edit', $service) }}" class="p-1.5 text-slate-600 hover:text-sky-600 hover:bg-slate-100 rounded-lg transition-colors">
                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Hapus/nonaktifkan layanan ini?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

