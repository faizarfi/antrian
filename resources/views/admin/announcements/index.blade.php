@extends('layouts.admin')

@section('page_title', 'Manajemen Pengumuman TV')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-slate-900">Pengumuman & Maklumat Pelayanan</h3>
            <p class="text-xs text-slate-500">Kelola pesan dan informasi resmi yang ditampilkan pada layar TV di ruang tunggu</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-700 hover:bg-sky-800 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Tambah Pengumuman</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold tracking-wider">
                    <tr>
                        <th class="px-5 py-3 w-16">Urutan</th>
                        <th class="px-5 py-3">Judul Pengumuman</th>
                        <th class="px-5 py-3">Isi Pengumuman</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($announcements as $item)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-3 font-mono font-bold text-slate-700">#{{ $item->order }}</td>
                            <td class="px-5 py-3 font-bold text-slate-900">{{ $item->title }}</td>
                            <td class="px-5 py-3 text-slate-600 max-w-md truncate">{{ $item->content }}</td>
                            <td class="px-5 py-3">
                                @if($item->is_active)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-500">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.announcements.edit', $item) }}" class="p-1.5 text-slate-500 hover:text-sky-600 hover:bg-slate-100 rounded-lg transition-colors">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.announcements.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada pengumuman dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

