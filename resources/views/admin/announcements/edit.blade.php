@extends('layouts.admin')

@section('page_title', 'Edit Pengumuman TV')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-900">Edit Pengumuman</h3>
            <p class="text-xs text-slate-500">Perbarui teks informasi tayangan layar TV</p>
        </div>
        <a href="{{ route('admin.announcements.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 inline-flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Judul Pengumuman</label>
                <input type="text" name="title" id="title" required value="{{ old('title', $announcement->title) }}"
                    class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">
                @error('title') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="content" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Isi Pesan Informasi</label>
                <textarea name="content" id="content" rows="4" required
                    class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">{{ old('content', $announcement->content) }}</textarea>
                @error('content') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="order" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Urutan Tampil</label>
                    <input type="number" name="order" id="order" required min="1" value="{{ old('order', $announcement->order) }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 font-mono">
                    @error('order') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-sky-600 border-slate-300 rounded focus:ring-sky-500">
                        <span class="text-sm font-medium text-slate-700">Aktifkan Pengumuman</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-semibold transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2 bg-sky-700 hover:bg-sky-800 text-white rounded-xl text-xs font-semibold shadow-sm transition-colors">Perbarui Pengumuman</button>
            </div>
        </form>
    </div>
</div>
@endsection

