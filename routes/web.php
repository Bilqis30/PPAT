<?php

use App\Http\Controllers\AdminLayananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DriveController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WhatsappSettingController;
use App\Models\DriveFile;
use App\Models\DriveFolder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - WebArsipp (PPAT Iwan Kurniawan, SH., MH., M.Kn)
|--------------------------------------------------------------------------
*/

// 1. Public Landing Page, Layanan & Materi
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
Route::get('/materi', [MateriController::class, 'index'])->name('materi.index');
Route::get('/materi/{slug}', [MateriController::class, 'show'])->name('materi.show');

// 2. Dedicated Login & Authentication Routes (/admin, /login, /portal)
Route::get('/admin', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin', [AuthController::class, 'login'])
    ->middleware(['guest', 'throttle:login'])
    ->name('login.post');
// Fallback aliases for /login and /portal to redirect to /admin
Route::get('/login', fn () => redirect()->route('login'));
Route::get('/portal', fn () => redirect()->route('login'));

// 3. Authenticated Routes (Requires Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Generic Profile Shortcut (Redirects to Role Profile)
    Route::get('/profile', function () {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.profile.edit')
            : redirect()->route('pimpinan.profile.edit');
    })->name('profile');

    // Shared Document Actions (Preview, Download, & Detail View)
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Shared Drive File & Folder Actions (Preview, Download, ZIP)
    Route::get('/drive/files/{file}/preview', [DriveController::class, 'preview'])->name('drive.files.preview');
    Route::get('/drive/files/{file}/download', [DriveController::class, 'download'])->name('drive.files.download');
    Route::get('/drive/folders/{folder}/download', [DriveController::class, 'downloadFolder'])->name('drive.folders.download');

    // ==========================================
    // A. ADMIN ROUTES (Protected by 'role:admin')
    // ==========================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        // Drive Berkas & Folder Management Routes (Google Drive Style)
        Route::get('/drive/folders/{folder?}', function ($folder = null) {
            if ($folder && DriveFolder::where('id', $folder)->exists()) {
                return redirect()->route('admin.drive.index', $folder);
            }

            return redirect()->route('admin.drive.index');
        });
        Route::get('/drive/files/{file?}', function ($file = null) {
            if ($file && ($driveFile = DriveFile::find($file))) {
                return redirect()->route('admin.drive.index', $driveFile->folder_id);
            }

            return redirect()->route('admin.drive.index');
        });
        Route::get('/drive/{folder?}', [DriveController::class, 'index'])->name('drive.index');
        Route::post('/drive/folders', [DriveController::class, 'storeFolder'])->name('drive.folders.store');
        Route::put('/drive/folders/{folder}', [DriveController::class, 'updateFolder'])->name('drive.folders.update');
        Route::delete('/drive/folders/{folder}', [DriveController::class, 'destroyFolder'])->name('drive.folders.destroy');
        Route::post('/drive/upload', [DriveController::class, 'upload'])->name('drive.upload');
        Route::put('/drive/files/{file}', [DriveController::class, 'updateFile'])->name('drive.files.update');
        Route::delete('/drive/files/{file}', [DriveController::class, 'destroyFile'])->name('drive.files.destroy');

        // Layanan & Panduan Management Routes
        Route::get('/layanan', [AdminLayananController::class, 'index'])->name('layanan.index');
        Route::get('/layanan/create', [AdminLayananController::class, 'create'])->name('layanan.create');
        Route::post('/layanan', [AdminLayananController::class, 'store'])->name('layanan.store');
        Route::get('/layanan/{layanan}/edit', [AdminLayananController::class, 'edit'])->name('layanan.edit');
        Route::put('/layanan/{layanan}', [AdminLayananController::class, 'update'])->name('layanan.update');
        Route::delete('/layanan/{layanan}', [AdminLayananController::class, 'destroy'])->name('layanan.destroy');

        // Materi / Artikel Management Routes
        Route::get('/materi', [MateriController::class, 'adminIndex'])->name('materi.index');
        Route::get('/materi/create', [MateriController::class, 'create'])->name('materi.create');
        Route::post('/materi', [MateriController::class, 'store'])->name('materi.store');
        Route::get('/materi/{materi}/edit', [MateriController::class, 'edit'])->name('materi.edit');
        Route::put('/materi/{materi}', [MateriController::class, 'update'])->name('materi.update');
        Route::post('/materi/{materi}/cover', [MateriController::class, 'updateCover'])->name('materi.update-cover');
        Route::delete('/materi/{materi}', [MateriController::class, 'destroy'])->name('materi.destroy');

        // Profile & Security Routes
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // WhatsApp Gateway Settings Routes
        Route::get('/settings/whatsapp', [WhatsappSettingController::class, 'edit'])->name('settings.whatsapp');
        Route::put('/settings/whatsapp', [WhatsappSettingController::class, 'update'])->name('settings.whatsapp.update');
    });

    // ==============================================
    // B. PIMPINAN ROUTES (Protected by 'role:pimpinan')
    // ==============================================
    Route::middleware('role:pimpinan')->prefix('pimpinan')->name('pimpinan.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'pimpinan'])->name('dashboard');
        Route::get('/monitoring', [DocumentController::class, 'monitoring'])->name('monitoring');
        Route::get('/drive/{folder?}', [DriveController::class, 'pimpinanIndex'])->name('drive.index');

        // Profile & Security Routes
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});
