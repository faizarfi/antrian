<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::orderBy('order', 'asc')->get();

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:5', 'uppercase', 'unique:services,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'color' => ['required', 'string', 'max:20'],
            'icon' => ['required', 'string', 'max:50'],
            'order' => ['required', 'integer'],
            'is_active' => ['boolean'],
        ]);

        Service::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'color' => $validated['color'],
            'icon' => $validated['icon'],
            'order' => $validated['order'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Layanan antrean berhasil ditambahkan.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:5', 'uppercase', Rule::unique('services')->ignore($service->id)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'color' => ['required', 'string', 'max:20'],
            'icon' => ['required', 'string', 'max:50'],
            'order' => ['required', 'integer'],
            'is_active' => ['boolean'],
        ]);

        $service->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'color' => $validated['color'],
            'icon' => $validated['icon'],
            'order' => $validated['order'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Layanan antrean berhasil diperbarui.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        if ($service->tickets()->count() > 0) {
            // Soft deactivation instead of deleting if it has existing ticket records
            $service->update(['is_active' => false]);

            return redirect()->route('admin.services.index')->with('warning', 'Layanan dinonaktifkan karena telah memiliki data tiket antrean terkait.');
        }

        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Layanan antrean berhasil dihapus.');
    }
}
