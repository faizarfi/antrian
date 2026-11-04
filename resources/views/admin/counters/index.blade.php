@extends('layouts.admin')

@section('page_title', 'Manajemen Loket Pelayanan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-slate-900">Daftar Loket Pelayanan</h3>
            <p class="text-xs text-slate-500">Kelola nomor loket fisik yang digunakan oleh petugas PST</p>
        </div>
        <a href="{{ route('admin.counters.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-700 hover:bg-sky-800 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Tambah Loket</span>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($counters as $counter)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-sky-50 border border-sky-100 text-sky-700 flex items-center justify-center font-extrabold text-xl">
                            {{ $counter->number }}
                        </div>
                        @if($counter->is_active)
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Aktif
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-500">
                                Nonaktif
                            </span>
                        @endif
                    </div>

                    <h4 class="text-lg font-bold text-slate-900 mb-1">{{ $counter->name }}</h4>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">{{ $counter->description ?? 'Loket pelayanan terpadu' }}</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Nomor: #{{ $counter->number }}</span>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.counters.edit', $counter) }}" class="p-1.5 text-slate-600 hover:text-sky-600 hover:bg-slate-100 rounded-lg transition-colors">
                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('admin.counters.destroy', $counter) }}" method="POST" onsubmit="return confirm('Hapus/nonaktifkan loket ini?');" class="inline">
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

