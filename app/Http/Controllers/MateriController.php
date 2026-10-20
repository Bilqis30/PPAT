<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MateriController extends Controller
{
    /**
     * Display public listing of materials/articles.
     */
    public function index(Request $request): View
    {
        $query = Materi::query();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('category', $request->input('kategori'));
        }

        $materis = $query->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('materi.index', compact('materis'));
    }

    /**
     * Display public single material/article detail.
     */
    public function show(string $slug): View
    {
        $materi = Materi::where('slug', $slug)->firstOrFail();

        $recentMateris = Materi::where('id', '!=', $materi->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('materi.show', compact('materi', 'recentMateris'));
    }

    /**
     * Admin: Listing of all materials for management.
     */
    public function adminIndex(Request $request): View
    {
        $query = Materi::query();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('category', $request->input('kategori'));
        }

        $materis = $query->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.materi.index', compact('materis'));
    }

    /**
     * Admin: Show form to create/write new material.
     */
    public function create(): View
    {
        return view('admin.materi.create');
    }

    /**
     * Admin: Store newly created material.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'published_at' => 'required|date',
            'author_name' => 'nullable|string|max:150',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
        ], [
            'title.required' => 'Judul materi wajib diisi.',
            'category.required' => 'Kategori materi wajib dipilih/diisi.',
            'published_at.required' => 'Tanggal terbit wajib diisi.',
            'cover_image.image' => 'File sampul harus berupa gambar valid (JPEG, PNG, JPG, WEBP).',
            'cover_image.max' => 'Ukuran gambar maksimal adalah 5MB.',
            'content.required' => 'Isi materi atau artikel wajib ditulis.',
        ]);

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
            $coverImagePath = $file->storeAs('materi', $filename, 'public');
        } elseif ($request->filled('preset_cover')) {
            $coverImagePath = $request->input('preset_cover');
        } elseif ($request->filled('custom_cover_url')) {
            $coverImagePath = $request->input('custom_cover_url');
        }

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Materi::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        Materi::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'cover_image' => $coverImagePath,
            'excerpt' => ($validated['excerpt'] ?? null) ?: Str::limit(strip_tags($validated['content']), 160),
            'content' => $validated['content'],
            'published_at' => $validated['published_at'],
            'author_name' => ($validated['author_name'] ?? null) ?: (Auth::user()?->name ?? 'Admin PPAT'),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.materi.index')->with('success', 'Materi/Artikel berhasil diterbitkan.');
    }

    /**
     * Admin: Show form to edit material.
     */
    public function edit(Materi $materi): View
    {
        return view('admin.materi.edit', compact('materi'));
    }

    /**
     * Admin: Update existing material.
     */
    public function update(Request $request, Materi $materi): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'published_at' => 'required|date',
            'author_name' => 'nullable|string|max:150',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'preset_cover' => 'nullable|string',
            'custom_cover_url' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
        ], [
            'title.required' => 'Judul materi wajib diisi.',
            'category.required' => 'Kategori materi wajib dipilih/diisi.',
            'published_at.required' => 'Tanggal terbit wajib diisi.',
            'cover_image.image' => 'File sampul harus berupa gambar valid (JPEG, PNG, JPG, WEBP).',
            'cover_image.max' => 'Ukuran gambar maksimal adalah 5MB.',
            'content.required' => 'Isi materi atau artikel wajib ditulis.',
        ]);

        if ($request->hasFile('cover_image')) {
            // Delete old file if it is in storage
            if ($materi->cover_image && ! str_starts_with($materi->cover_image, 'images/')) {
                Storage::disk('public')->delete($materi->cover_image);
            }

            $file = $request->file('cover_image');
            $filename = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
            $materi->cover_image = $file->storeAs('materi', $filename, 'public');
        } elseif ($request->filled('preset_cover')) {
            $materi->cover_image = $request->input('preset_cover');
        } elseif ($request->filled('custom_cover_url')) {
            $materi->cover_image = $request->input('custom_cover_url');
        }

        $materi->title = $validated['title'];
        $materi->category = $validated['category'];
        $materi->published_at = $validated['published_at'];
        $materi->author_name = ($validated['author_name'] ?? null) ?: $materi->author_name;
        $materi->excerpt = ($validated['excerpt'] ?? null) ?: Str::limit(strip_tags($validated['content']), 160);
        $materi->content = $validated['content'];
        $materi->save();

        return redirect()->route('admin.materi.index')->with('success', 'Materi/Artikel berhasil diperbarui.');
    }

    /**
     * Admin: Delete material.
     */
    public function destroy(Materi $materi): RedirectResponse
    {
        if ($materi->cover_image && ! str_starts_with($materi->cover_image, 'images/')) {
            Storage::disk('public')->delete($materi->cover_image);
        }

        $materi->delete();

        return redirect()->route('admin.materi.index')->with('success', 'Materi/Artikel berhasil dihapus.');
    }

    /**
     * Admin: Quick update only the cover/background image of material.
     */
    public function updateCover(Request $request, Materi $materi): RedirectResponse
    {
        $request->validate([
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'preset_cover' => 'nullable|string',
            'custom_cover_url' => 'nullable|string',
        ], [
            'cover_image.image' => 'File sampul harus berupa gambar valid (JPEG, PNG, JPG, WEBP).',
            'cover_image.max' => 'Ukuran gambar maksimal adalah 5MB.',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($materi->cover_image && ! str_starts_with($materi->cover_image, 'images/')) {
                Storage::disk('public')->delete($materi->cover_image);
            }

            $file = $request->file('cover_image');
            $filename = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
            $materi->cover_image = $file->storeAs('materi', $filename, 'public');
        } elseif ($request->filled('preset_cover')) {
            $materi->cover_image = $request->input('preset_cover');
        } elseif ($request->filled('custom_cover_url')) {
            $materi->cover_image = $request->input('custom_cover_url');
        }

        $materi->save();

        return back()->with('success', 'Background / Foto sampul materi "'.$materi->title.'" berhasil diperbarui.');
    }
}
