@extends('layouts.admin')

@section('page_title', 'Edit Layanan Antrean')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-900">Edit Layanan: {{ $service->name }}</h3>
            <p class="text-xs text-slate-500">Ubah konfigurasi layanan kode {{ $service->code }}</p>
        </div>
        <a href="{{ route('admin.services.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 inline-flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.services.update', $service) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="code" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Kode Huruf</label>
                    <input type="text" name="code" id="code" required maxlength="2" value="{{ old('code', $service->code) }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 uppercase font-mono font-bold">
                    @error('code') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Nama Layanan</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $service->name) }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">
                    @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Deskripsi Singkat Layanan</label>
                <textarea name="description" id="description" rows="3"
                    class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500">{{ old('description', $service->description) }}</textarea>
                @error('description') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="color" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Warna Label</label>
                    <input type="color" name="color" id="color" value="{{ old('color', $service->color) }}"
                        class="h-10 w-full rounded-xl cursor-pointer bg-slate-50 border border-slate-200 p-1">
                    @error('color') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="icon" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Nama Ikon Lucide</label>
                    <input type="text" name="icon" id="icon" required value="{{ old('icon', $service->icon) }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 font-mono">
                    @error('icon') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="order" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1">Nomor Urut</label>
                    <input type="number" name="order" id="order" required min="1" value="{{ old('order', $service->order) }}"
                        class="block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 font-mono">
                    @error('order') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-sky-600 border-slate-300 rounded focus:ring-sky-500">
                    <span class="text-sm font-medium text-slate-700">Layanan Aktif</span>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.services.index') }}" class="px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-semibold transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2 bg-sky-700 hover:bg-sky-800 text-white rounded-xl text-xs font-semibold shadow-sm transition-colors">Perbarui Layanan</button>
            </div>
        </form>
    </div>
</div>
@endsection

