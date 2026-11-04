<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CounterController extends Controller
{
    public function index(): View
    {
        $counters = Counter::orderBy('number', 'asc')->get();

        return view('admin.counters.index', compact('counters'));
    }

    public function create(): View
    {
        $nextNumber = (Counter::max('number') ?? 0) + 1;

        return view('admin.counters.create', compact('nextNumber'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'number' => ['required', 'integer', 'unique:counters,number'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        Counter::create([
            'number' => $validated['number'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.counters.index')->with('success', 'Loket pelayanan berhasil ditambahkan.');
    }

    public function edit(Counter $counter): View
    {
        return view('admin.counters.edit', compact('counter'));
    }

    public function update(Request $request, Counter $counter): RedirectResponse
    {
        $validated = $request->validate([
            'number' => ['required', 'integer', Rule::unique('counters')->ignore($counter->id)],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $counter->update([
            'number' => $validated['number'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.counters.index')->with('success', 'Data loket berhasil diperbarui.');
    }

    public function destroy(Counter $counter): RedirectResponse
    {
        if ($counter->tickets()->count() > 0) {
            $counter->update(['is_active' => false]);

            return redirect()->route('admin.counters.index')->with('warning', 'Loket dinonaktifkan karena telah memiliki data tiket antrean.');
        }

        $counter->delete();

        return redirect()->route('admin.counters.index')->with('success', 'Loket pelayanan berhasil dihapus.');
    }
}
