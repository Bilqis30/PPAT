<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminLayananController extends Controller
{
    /**
     * Display listing of all services for admin management.
     */
    public function index(Request $request): View
    {
        $query = Layanan::query();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('badge', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $services = $query->orderBy('order_num')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.layanan.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create(): View
    {
        $nextOrder = (Layanan::max('order_num') ?? 0) + 1;

        return view('admin.layanan.create', compact('nextOrder'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:100|unique:layanans,slug',
            'badge' => 'nullable|string|max:100',
            'badge_color' => 'required|string|max:50',
            'icon' => 'required|string|max:50',
            'description' => 'required|string',
            'steps' => 'nullable|string',
            'requirements_penjual' => 'nullable|string',
            'requirements_pembeli' => 'nullable|string',
            'estimated_time' => 'nullable|string|max:255',
            'order_num' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'Nama / Judul layanan wajib diisi.',
            'badge_color.required' => 'Warna badge wajib dipilih.',
            'icon.required' => 'Nama ikon Lucide wajib diisi.',
            'description.required' => 'Deskripsi layanan wajib diisi.',
        ]);

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $baseSlug = $slug;
        $counter = 1;
        while (Layanan::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        Layanan::create([
            'slug' => $slug,
            'title' => $validated['title'],
            'badge' => $validated['badge'] ?? null,
            'badge_color' => $validated['badge_color'] ?? 'emerald',
            'icon' => $validated['icon'] ?? 'clipboard-list',
            'description' => $validated['description'],
            'steps' => Layanan::linesToArray($validated['steps'] ?? ''),
            'requirements_penjual' => Layanan::linesToArray($validated['requirements_penjual'] ?? ''),
            'requirements_pembeli' => Layanan::linesToArray($validated['requirements_pembeli'] ?? ''),
            'estimated_time' => $validated['estimated_time'] ?? null,
            'order_num' => (int) ($validated['order_num'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan & panduan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Layanan $layanan): View
    {
        return view('admin.layanan.edit', compact('layanan'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Layanan $layanan): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:100|unique:layanans,slug,'.$layanan->id,
            'badge' => 'nullable|string|max:100',
            'badge_color' => 'required|string|max:50',
            'icon' => 'required|string|max:50',
            'description' => 'required|string',
            'steps' => 'nullable|string',
            'requirements_penjual' => 'nullable|string',
            'requirements_pembeli' => 'nullable|string',
            'estimated_time' => 'nullable|string|max:255',
            'order_num' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'Nama / Judul layanan wajib diisi.',
            'badge_color.required' => 'Warna badge wajib dipilih.',
            'icon.required' => 'Nama ikon Lucide wajib diisi.',
            'description.required' => 'Deskripsi layanan wajib diisi.',
        ]);

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        if ($slug !== $layanan->slug) {
            $baseSlug = $slug;
            $counter = 1;
            while (Layanan::where('slug', $slug)->where('id', '!=', $layanan->id)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }
        }

        $layanan->slug = $slug;
        $layanan->title = $validated['title'];
        $layanan->badge = $validated['badge'] ?? null;
        $layanan->badge_color = $validated['badge_color'] ?? 'emerald';
        $layanan->icon = $validated['icon'] ?? 'clipboard-list';
        $layanan->description = $validated['description'];
        $layanan->steps = Layanan::linesToArray($validated['steps'] ?? '');
        $layanan->requirements_penjual = Layanan::linesToArray($validated['requirements_penjual'] ?? '');
        $layanan->requirements_pembeli = Layanan::linesToArray($validated['requirements_pembeli'] ?? '');
        $layanan->estimated_time = $validated['estimated_time'] ?? null;
        $layanan->order_num = (int) ($validated['order_num'] ?? 0);
        $layanan->is_active = $request->boolean('is_active', true);
        $layanan->save();

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan & panduan berhasil diperbarui.');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Layanan $layanan): RedirectResponse
    {
        $layanan->delete();

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan & panduan berhasil dihapus.');
    }
}
