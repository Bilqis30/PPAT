<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DriveFile;
use App\Models\DriveFolder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class DriveController extends Controller
{
    /**
     * Cache for resolved folder hierarchies in current request
     *
     * @var array<string, int>
     */
    private array $folderCache = [];

    /**
     * Admin: Google Drive style explorer for folders and files.
     */
    public function index(Request $request, ?DriveFolder $folder = null): View
    {
        $search = $request->query('q');
        $viewMode = $request->query('view', 'grid');

        // Breadcrumbs for folder navigation
        $breadcrumbs = $folder ? $folder->getAncestors() : [];

        // Query folders and files
        if ($search) {
            $folders = DriveFolder::where('name', 'like', "%{$search}%")
                ->withCount(['children', 'files'])
                ->orderBy('name')
                ->get();

            $files = DriveFile::where('name', 'like', "%{$search}%")
                ->with(['folder', 'user'])
                ->latest()
                ->paginate(50)
                ->withQueryString();
        } else {
            $parentId = $folder ? $folder->id : null;

            $folders = DriveFolder::where('parent_id', $parentId)
                ->withCount(['children', 'files'])
                ->orderBy('name')
                ->get();

            $files = DriveFile::where('folder_id', $parentId)
                ->with('user')
                ->orderBy('name')
                ->paginate(60)
                ->withQueryString();
        }

        // Global stats for Drive summary
        $totalFoldersCount = DriveFolder::count();
        $totalFilesCount = DriveFile::count();
        $totalSizeBytes = (int) DriveFile::sum('file_size');
        $totalStorageFormatted = $this->formatBytes($totalSizeBytes);

        // Cross-search query for Document Archives
        $matchedDocuments = collect();
        if ($search) {
            $matchedDocuments = Document::where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
                ->with('user')
                ->latest('document_date')
                ->limit(10)
                ->get();
        }

        return view('admin.drive.index', [
            'currentFolder' => $folder,
            'breadcrumbs' => $breadcrumbs,
            'folders' => $folders,
            'files' => $files,
            'search' => $search,
            'viewMode' => $viewMode,
            'totalFoldersCount' => $totalFoldersCount,
            'totalFilesCount' => $totalFilesCount,
            'totalStorageFormatted' => $totalStorageFormatted,
            'isReadOnly' => false,
            'matchedDocuments' => $matchedDocuments,
        ]);
    }

    /**
     * Pimpinan: Read-only monitoring explorer for Drive folders and files.
     */
    public function pimpinanIndex(Request $request, ?DriveFolder $folder = null): View
    {
        $search = $request->query('q') ?: $request->query('search');
        $viewMode = $request->query('view', 'grid');

        $breadcrumbs = $folder ? $folder->getAncestors() : [];

        if ($search) {
            $folders = DriveFolder::where('name', 'like', "%{$search}%")
                ->withCount(['children', 'files'])
                ->orderBy('name')
                ->get();

            $files = DriveFile::where('name', 'like', "%{$search}%")
                ->with(['folder', 'user'])
                ->latest()
                ->paginate(50)
                ->withQueryString();
        } else {
            $parentId = $folder ? $folder->id : null;

            $folders = DriveFolder::where('parent_id', $parentId)
                ->withCount(['children', 'files'])
                ->orderBy('name')
                ->get();

            $files = DriveFile::where('folder_id', $parentId)
                ->with('user')
                ->orderBy('name')
                ->paginate(60)
                ->withQueryString();
        }

        $totalFoldersCount = DriveFolder::count();
        $totalFilesCount = DriveFile::count();
        $totalSizeBytes = (int) DriveFile::sum('file_size');
        $totalStorageFormatted = $this->formatBytes($totalSizeBytes);

        // Cross-search query for Document Archives
        $matchedDocuments = collect();
        if ($search) {
            $matchedDocuments = Document::where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
                ->with('user')
                ->latest('document_date')
                ->limit(10)
                ->get();
        }

        return view('admin.drive.index', [
            'currentFolder' => $folder,
            'breadcrumbs' => $breadcrumbs,
            'folders' => $folders,
            'files' => $files,
            'search' => $search,
            'viewMode' => $viewMode,
            'totalFoldersCount' => $totalFoldersCount,
            'totalFilesCount' => $totalFilesCount,
            'totalStorageFormatted' => $totalStorageFormatted,
            'isReadOnly' => true,
            'matchedDocuments' => $matchedDocuments,
        ]);
    }

    /**
     * Create a new folder.
     */
    public function storeFolder(Request $request): JsonResponse|RedirectResponse
    {
        abort_if(! $request->user()?->isAdmin(), 403, 'Hanya Administrator yang dapat membuat folder.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:drive_folders,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'color' => ['nullable', 'string', 'max:30'],
        ]);

        $folder = DriveFolder::create([
            'name' => trim($validated['name']),
            'parent_id' => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? 'amber',
            'user_id' => Auth::id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Folder '{$folder->name}' berhasil dibuat.",
                'folder' => $folder,
            ]);
        }

        return redirect()->back()->with('success', "Folder '{$folder->name}' berhasil dibuat.");
    }

    /**
     * Rename an existing folder.
     */
    public function updateFolder(Request $request, DriveFolder $folder): JsonResponse|RedirectResponse
    {
        abort_if(! $request->user()?->isAdmin(), 403, 'Hanya Administrator yang dapat mengubah folder.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $folder->update([
            'name' => trim($validated['name']),
            'description' => $validated['description'] ?? $folder->description,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Nama folder berhasil diperbarui.',
                'folder' => $folder,
            ]);
        }

        return redirect()->back()->with('success', "Folder berhasil diperbarui menjadi '{$folder->name}'.");
    }

    /**
     * Delete a folder and its contents.
     */
    public function destroyFolder(Request $request, DriveFolder $folder): JsonResponse|RedirectResponse
    {
        abort_if(! $request->user()?->isAdmin(), 403, 'Hanya Administrator yang dapat menghapus folder.');

        $folderName = $folder->name;
        $parentId = $folder->parent_id;

        // Recursively clean up storage files
        $this->deleteFolderStorageRecursively($folder);

        // Cascade delete records in database
        $folder->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Folder '{$folderName}' beserta isinya berhasil dihapus.",
            ]);
        }

        if ($parentId) {
            return redirect()->route('admin.drive.index', $parentId)
                ->with('success', "Folder '{$folderName}' beserta isinya berhasil dihapus.");
        }

        return redirect()->route('admin.drive.index')
            ->with('success', "Folder '{$folderName}' beserta isinya berhasil dihapus.");
    }

    /**
     * Async/Batch File Upload endpoint (supports single file or bulk batch uploads with folder hierarchy).
     */
    public function upload(Request $request): JsonResponse
    {
        abort_if(! $request->user()?->isAdmin(), 403, 'Hanya Administrator yang dapat mengunggah berkas.');

        $targetFolderId = $request->input('folder_id');

        // BATCH MODE: Uploading multiple files in one HTTP request for blazing speed
        if ($request->hasFile('files')) {
            $request->validate([
                'files.*' => [
                    'required',
                    'file',
                    'mimes:pdf,docx,doc,xls,xlsx,ppt,pptx,jpg,jpeg,png,webp,zip,rar,txt,csv,rtf',
                    'max:102400', // max 100MB per file
                ],
                'folder_id' => ['nullable', 'exists:drive_folders,id'],
                'relative_paths' => ['nullable', 'array'],
                'relative_paths.*' => ['nullable', 'string', 'max:1000'],
            ]);

            $files = $request->file('files');
            $relativePaths = $request->input('relative_paths', []);
            $uploadedList = [];

            DB::beginTransaction();

            try {
                foreach ($files as $index => $uploadedFile) {
                    $originalName = $uploadedFile->getClientOriginalName();
                    $fileSize = $uploadedFile->getSize();
                    $mimeType = $uploadedFile->getClientMimeType();
                    $extension = strtolower($uploadedFile->getClientOriginalExtension());
                    $relativePath = $relativePaths[$index] ?? null;

                    $fileFolderId = $targetFolderId ? (int) $targetFolderId : null;
                    if (! empty($relativePath)) {
                        $fileFolderId = $this->resolveSubfolderHierarchy($fileFolderId, $relativePath);
                    }

                    $storedPath = $uploadedFile->store('drive', 'local');

                    $driveFile = DriveFile::create([
                        'folder_id' => $fileFolderId,
                        'user_id' => Auth::id(),
                        'name' => $originalName,
                        'file_path' => $storedPath,
                        'file_size' => $fileSize,
                        'mime_type' => $mimeType,
                        'extension' => $extension,
                        'relative_path' => $relativePath,
                    ]);

                    $uploadedList[] = [
                        'id' => $driveFile->id,
                        'name' => $driveFile->name,
                        'folder_id' => $driveFile->folder_id,
                        'file_size' => $driveFile->file_size,
                        'formatted_size' => $driveFile->formatted_file_size,
                        'icon' => $driveFile->icon,
                    ];
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'count' => count($uploadedList),
                    'files' => $uploadedList,
                ]);
            } catch (\Throwable $e) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengunggah batch: '.$e->getMessage(),
                ], 500);
            }
        }

        // SINGLE FILE MODE (Fallback for 1-by-1 upload)
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf,docx,doc,xls,xlsx,ppt,pptx,jpg,jpeg,png,webp,zip,rar,txt,csv,rtf',
                'max:102400', // max 100MB per file
            ],
            'folder_id' => ['nullable', 'exists:drive_folders,id'],
            'relative_path' => ['nullable', 'string', 'max:1000'],
        ]);

        $uploadedFile = $request->file('file');
        $originalName = $uploadedFile->getClientOriginalName();
        $fileSize = $uploadedFile->getSize();
        $mimeType = $uploadedFile->getClientMimeType();
        $extension = strtolower($uploadedFile->getClientOriginalExtension());

        $relativePath = $request->input('relative_path');
        $fileFolderId = $targetFolderId ? (int) $targetFolderId : null;
        if (! empty($relativePath)) {
            $fileFolderId = $this->resolveSubfolderHierarchy($fileFolderId, $relativePath);
        }

        $storedPath = $uploadedFile->store('drive', 'local');

        $driveFile = DriveFile::create([
            'folder_id' => $fileFolderId,
            'user_id' => Auth::id(),
            'name' => $originalName,
            'file_path' => $storedPath,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'extension' => $extension,
            'relative_path' => $relativePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Berkas '{$originalName}' berhasil diunggah.",
            'file' => [
                'id' => $driveFile->id,
                'name' => $driveFile->name,
                'folder_id' => $driveFile->folder_id,
                'file_size' => $driveFile->file_size,
                'formatted_size' => $driveFile->formatted_file_size,
                'icon' => $driveFile->icon,
            ],
        ]);
    }

    /**
     * Rename an existing file.
     */
    public function updateFile(Request $request, DriveFile $file): JsonResponse|RedirectResponse
    {
        abort_if(! $request->user()?->isAdmin(), 403, 'Hanya Administrator yang dapat mengubah nama berkas.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $file->update([
            'name' => trim($validated['name']),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Nama berkas berhasil diperbarui.',
                'file' => $file,
            ]);
        }

        return redirect()->back()->with('success', 'Nama berkas berhasil diperbarui.');
    }

    /**
     * Delete an individual file.
     */
    public function destroyFile(Request $request, DriveFile $file): JsonResponse|RedirectResponse
    {
        abort_if(! $request->user()?->isAdmin(), 403, 'Hanya Administrator yang dapat menghapus berkas.');

        $fileName = $file->name;
        $folderId = $file->folder_id;

        // Remove from physical storage
        if (Storage::disk('local')->exists($file->file_path)) {
            Storage::disk('local')->delete($file->file_path);
        }

        $file->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Berkas '{$fileName}' berhasil dihapus.",
            ]);
        }

        if ($folderId) {
            return redirect()->route('admin.drive.index', $folderId)
                ->with('success', "Berkas '{$fileName}' berhasil dihapus.");
        }

        return redirect()->route('admin.drive.index')
            ->with('success', "Berkas '{$fileName}' berhasil dihapus.");
    }

    /**
     * Preview file inline (PDF, Image, Text).
     */
    public function preview(DriveFile $file): BinaryFileResponse|RedirectResponse
    {
        $disk = Storage::disk('local');

        if (! $disk->exists($file->file_path)) {
            return redirect()->back()->with('error', 'Berkas fisik tidak ditemukan di server.');
        }

        $fullPath = $disk->path($file->file_path);
        $safeFileName = str_replace(["\r", "\n", '"', ';'], '', basename($file->name));

        $contentType = $file->mime_type ?: mime_content_type($fullPath) ?: 'application/octet-stream';

        return response()->file($fullPath, [
            'Content-Type' => $contentType,
            'Content-Disposition' => "inline; filename=\"{$safeFileName}\"",
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Download individual file.
     */
    public function download(DriveFile $file): BinaryFileResponse|RedirectResponse
    {
        $disk = Storage::disk('local');

        if (! $disk->exists($file->file_path)) {
            return redirect()->back()->with('error', 'Berkas fisik tidak ditemukan di server.');
        }

        $safeFileName = str_replace(["\r", "\n", '"', ';'], '', basename($file->name));

        return $disk->download($file->file_path, $safeFileName, [
            'Content-Type' => $file->mime_type ?: 'application/octet-stream',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Download entire folder as a single compressed ZIP file.
     */
    public function downloadFolder(DriveFolder $folder): BinaryFileResponse|RedirectResponse
    {
        if (! class_exists('ZipArchive')) {
            return redirect()->back()->with('error', 'Ekstensi PHP ZipArchive tidak aktif pada server ini.');
        }

        $zip = new ZipArchive;
        $tempDir = storage_path('app/private/temp');
        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipFileName = 'folder_'.$folder->id.'_'.time().'.zip';
        $zipFilePath = $tempDir.DIRECTORY_SEPARATOR.$zipFileName;

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return redirect()->back()->with('error', 'Gagal membuat file arsip ZIP.');
        }

        // Recursively pack files and subfolders into the ZIP
        $this->addFolderToZip($folder, $zip, '');

        $zip->close();

        $safeDownloadName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $folder->name).'.zip';

        return response()->download($zipFilePath, $safeDownloadName, [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Recursively add a folder's children and files into a ZipArchive.
     */
    private function addFolderToZip(DriveFolder $folder, ZipArchive $zip, string $currentPath): void
    {
        $folderPath = ($currentPath !== '') ? $currentPath.'/'.$folder->name : $folder->name;
        $zip->addEmptyDir($folderPath);

        // Add files in this folder
        $files = $folder->files;
        $disk = Storage::disk('local');

        foreach ($files as $file) {
            if ($disk->exists($file->file_path)) {
                $filePathOnDisk = $disk->path($file->file_path);
                $zip->addFile($filePathOnDisk, $folderPath.'/'.$file->name);
            }
        }

        // Recursively add child folders
        foreach ($folder->children as $childFolder) {
            $this->addFolderToZip($childFolder, $zip, $folderPath);
        }
    }

    /**
     * Recursively remove files of a folder from disk.
     */
    private function deleteFolderStorageRecursively(DriveFolder $folder): void
    {
        $disk = Storage::disk('local');

        foreach ($folder->files as $file) {
            if ($disk->exists($file->file_path)) {
                $disk->delete($file->file_path);
            }
        }

        foreach ($folder->children as $child) {
            $this->deleteFolderStorageRecursively($child);
        }
    }

    /**
     * Resolve or automatically create nested subfolder records based on relative_path (with in-memory memoization).
     */
    private function resolveSubfolderHierarchy(?int $rootFolderId, string $relativePath): ?int
    {
        $normalizedPath = trim(str_replace('\\', '/', $relativePath), '/');
        $cacheKey = ($rootFolderId ?? 'root').':'.dirname($normalizedPath);

        if (isset($this->folderCache[$cacheKey])) {
            return $this->folderCache[$cacheKey];
        }

        $parts = explode('/', $normalizedPath);

        // Pop off the file name at the end
        array_pop($parts);

        if (empty($parts)) {
            $this->folderCache[$cacheKey] = $rootFolderId;

            return $rootFolderId;
        }

        $currentParentId = $rootFolderId;

        foreach ($parts as $folderName) {
            $cleanName = trim($folderName);
            if ($cleanName === '') {
                continue;
            }

            $stepKey = ($currentParentId ?? 'root').':'.$cleanName;
            if (isset($this->folderCache[$stepKey])) {
                $currentParentId = $this->folderCache[$stepKey];

                continue;
            }

            $folder = DriveFolder::firstOrCreate([
                'name' => $cleanName,
                'parent_id' => $currentParentId,
            ], [
                'user_id' => Auth::id(),
                'color' => 'amber',
            ]);

            $this->folderCache[$stepKey] = $folder->id;
            $currentParentId = $folder->id;
        }

        $this->folderCache[$cacheKey] = $currentParentId;

        return $currentParentId;
    }

    /**
     * Format bytes into human readable format.
     */
    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1).' KB';
        }

        return $bytes.' B';
    }
}
