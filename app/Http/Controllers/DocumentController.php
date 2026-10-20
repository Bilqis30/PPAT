<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use App\Models\DriveFile;
use App\Models\DriveFolder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    /**
     * Admin: Archive list with search, filter, and management actions.
     */
    public function index(Request $request): View
    {
        $query = Document::with('user')->latest('document_date');

        $this->applyFilters($query, $request);

        $documents = $query->paginate(10)->withQueryString();

        $search = $request->input('search') ?: $request->input('q');
        $matchedDriveFiles = collect();
        $matchedDriveFolders = collect();

        if ($search) {
            $matchedDriveFiles = DriveFile::where('name', 'like', "%{$search}%")
                ->with(['folder', 'user'])
                ->latest()
                ->limit(12)
                ->get();

            $matchedDriveFolders = DriveFolder::where('name', 'like', "%{$search}%")
                ->withCount(['files', 'children'])
                ->limit(6)
                ->get();
        }

        return view('admin.documents.index', compact('documents', 'matchedDriveFiles', 'matchedDriveFolders', 'search'));
    }

    /**
     * Pimpinan: Read-only monitoring archive list with search, filter, and preview/download.
     */
    public function monitoring(Request $request): View
    {
        $query = Document::with('user')->latest('document_date');

        $this->applyFilters($query, $request);

        $documents = $query->paginate(12)->withQueryString();

        $search = $request->input('search') ?: $request->input('q');
        $matchedDriveFiles = collect();
        $matchedDriveFolders = collect();

        if ($search) {
            $matchedDriveFiles = DriveFile::where('name', 'like', "%{$search}%")
                ->with(['folder', 'user'])
                ->latest()
                ->limit(12)
                ->get();

            $matchedDriveFolders = DriveFolder::where('name', 'like', "%{$search}%")
                ->withCount(['files', 'children'])
                ->limit(6)
                ->get();
        }

        return view('pimpinan.monitoring', compact('documents', 'matchedDriveFiles', 'matchedDriveFolders', 'search'));
    }

    /**
     * Admin: Display document upload form with dynamic fields.
     */
    public function create(): View
    {
        return view('admin.documents.create');
    }

    /**
     * Admin: Store uploaded document with strict validation and private storage.
     */
    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        // Secondary defense check against privilege escalation
        abort_if(! $request->user()?->isAdmin(), 403, 'Aksi ini hanya dapat dilakukan oleh Administrator.');

        $validated = $request->validated();
        $file = $request->file('file');
        $originalFileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();

        // Anti-Gravity SQLite Architecture: Store raw physical file in storage/app/public/archives
        // Keep SQLite database lean and fast by only saving the file path string
        $storedPath = $file->store('archives', 'public');

        Document::create([
            'document_number' => $validated['type'] === 'Berkas' ? null : ($validated['document_number'] ?? null),
            'document_date' => $validated['document_date'],
            'tanggal_acara' => $validated['type'] === 'Surat Masuk' ? ($validated['tanggal_acara'] ?? null) : null,
            'title' => $validated['title'],
            'type' => $validated['type'],
            'sub_type' => $validated['type'] === 'Laporan' ? $validated['sub_type'] : null,
            'description' => $validated['description'] ?? null,
            'file_path' => $storedPath,
            'file_name' => $originalFileName,
            'file_size' => $fileSize,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.documents.index')
            ->with('success', "Dokumen '{$validated['title']}' berhasil diarsipkan.");
    }

    /**
     * Admin: Display form edit dokumen arsip.
     */
    public function edit(Request $request, Document $document): View
    {
        abort_if(! $request->user()?->isAdmin(), 403, 'Aksi ini hanya dapat dilakukan oleh Administrator.');

        return view('admin.documents.edit', compact('document'));
    }

    /**
     * Admin: Update data dokumen arsip, ganti file fisik jika ada yang baru diunggah.
     */
    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $validated = $request->validated();

        $newFilePath = $document->file_path;
        $newFileName = $document->file_name;
        $newFileSize = $document->file_size;

        // Jika ada file baru diupload, hapus file lama & simpan yang baru ke storage/app/public/archives
        if ($request->hasFile('file')) {
            if ($document->file_path) {
                if (Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                } elseif (Storage::disk('local')->exists($document->file_path)) {
                    Storage::disk('local')->delete($document->file_path);
                }
            }

            $file = $request->file('file');
            $newFilePath = $file->store('archives', 'public');
            $newFileName = $file->getClientOriginalName();
            $newFileSize = $file->getSize();
        }

        $document->update([
            'document_number' => $validated['type'] === 'Berkas' ? null : ($validated['document_number'] ?? null),
            'document_date' => $validated['document_date'],
            'tanggal_acara' => $validated['type'] === 'Surat Masuk' ? ($validated['tanggal_acara'] ?? null) : null,
            'title' => $validated['title'],
            'type' => $validated['type'],
            'sub_type' => $validated['type'] === 'Laporan' ? $validated['sub_type'] : null,
            'description' => $validated['description'] ?? null,
            'file_path' => $newFilePath,
            'file_name' => $newFileName,
            'file_size' => $newFileSize,
        ]);

        return redirect()->route('documents.show', $document)
            ->with('success', "Dokumen '{$document->title}' berhasil diperbarui.");
    }

    /**
     * Display single document detail (returns view or JSON).
     */
    public function show(Request $request, Document $document): View
    {
        abort_if(! in_array($request->user()?->role, ['admin', 'pimpinan'], true), 403, 'Akses ditolak.');

        $document->load('user');

        return view('documents.show', compact('document'));
    }

    /**
     * Stream document file directly in the browser (Inline Preview) with strict security headers.
     */
    public function preview(Request $request, Document $document): BinaryFileResponse
    {
        abort_if(! in_array($request->user()?->role, ['admin', 'pimpinan'], true), 403, 'Akses ditolak.');

        $disk = Storage::disk('public');
        $filePath = $document->file_path;

        // Fallback check for backward compatibility with existing legacy local storage archives
        if (! $disk->exists($filePath)) {
            if (Storage::disk('local')->exists($filePath)) {
                $disk = Storage::disk('local');
            } else {
                abort(404, 'File dokumen tidak ditemukan di server penyimpanan arsip.');
            }
        }

        $fullPath = $disk->path($filePath);
        $safeFileName = str_replace(["\r", "\n", '"', ';'], '', basename($document->file_name));
        $mimeType = $disk->mimeType($filePath) ?: 'application/pdf';

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="'.$safeFileName.'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Download the document archive file safely.
     */
    public function download(Request $request, Document $document): StreamedResponse
    {
        abort_if(! in_array($request->user()?->role, ['admin', 'pimpinan'], true), 403, 'Akses ditolak.');

        $disk = Storage::disk('public');
        $filePath = $document->file_path;

        if (! $disk->exists($filePath)) {
            if (Storage::disk('local')->exists($filePath)) {
                $disk = Storage::disk('local');
            } else {
                abort(404, 'File dokumen tidak ditemukan di server penyimpanan arsip.');
            }
        }

        $safeFileName = str_replace(["\r", "\n", '"', ';'], '', basename($document->file_name));
        $mimeType = $disk->mimeType($filePath) ?: 'application/octet-stream';

        return $disk->download($filePath, $safeFileName, [
            'Content-Type' => $mimeType,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Admin: Delete document and automatically remove physical file via Eloquent model event.
     */
    public function destroy(Request $request, Document $document): RedirectResponse
    {
        abort_if(! $request->user()?->isAdmin(), 403, 'Aksi penghapusan arsip hanya dapat dilakukan oleh Administrator.');

        $title = $document->title;

        // Delete record from database (File Deletion Cleanup is handled automatically by Document::booted deleting hook)
        $document->delete();

        return redirect()->back()
            ->with('success', "Dokumen '{$title}' dan berkas fisik terkait telah berhasil dihapus dari sistem.");
    }

    /**
     * Helper to apply common search and filtering.
     */
    private function applyFilters($query, Request $request): void
    {
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            if ($type === 'Laporan BPN') {
                $query->where('type', 'Laporan')->where('sub_type', 'Laporan BPN');
            } elseif ($type === 'Laporan Dispenda') {
                $query->where('type', 'Laporan')->where('sub_type', 'Laporan Dispenda');
            } else {
                $query->where('type', $type);
            }
        }

        if ($subType = $request->input('sub_type')) {
            $query->where('sub_type', $subType);
        }

        if ($startDate = $request->input('start_date')) {
            $query->whereDate('document_date', '>=', $startDate);
        }

        if ($endDate = $request->input('end_date')) {
            $query->whereDate('document_date', '<=', $endDate);
        }
    }
}
