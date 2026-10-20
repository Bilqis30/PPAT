@extends('layouts.app')

@section('title', 'Drive Berkas Proyek')
@section('page_title', 'Drive Berkas & Proyek')
@section('page_subtitle', $currentFolder ? 'Folder: ' . $currentFolder->name : 'Repositori penyimpanan folder proyek & berkas massal')

@section('content')
<div class="space-y-4 sm:space-y-6" id="drive-app">

    <!-- TOP TOOLBAR (Google Drive Style Responsive Toolbar) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-3.5 sm:p-5">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3 sm:gap-4">
            
            <!-- Left & Top Row on Mobile: Actions (+ Baru, Unduh ZIP, View Switcher) -->
            <div class="flex items-center justify-between gap-2.5 flex-wrap">
                <div class="flex items-center gap-2 flex-wrap">
                    @if(!$isReadOnly)
                        <!-- Dropdown + BARU -->
                        <div class="relative" id="new-action-dropdown-container">
                            <button type="button" onclick="toggleNewMenu()" 
                                    class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 sm:py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-semibold text-xs sm:text-sm shadow-sm transition hover:shadow-md">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                <span>Baru</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 opacity-70"></i>
                            </button>

                            <div id="new-menu-dropdown" 
                                 class="hidden absolute left-0 mt-2 w-64 max-w-[calc(100vw-2rem)] bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-40 transition-all">
                                <button type="button" onclick="openCreateFolderModal()" 
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-medium text-slate-700 hover:bg-slate-50 transition text-left">
                                    <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                        <i data-lucide="folder-plus" class="w-4 h-4"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <span class="block font-semibold text-slate-900">Folder Baru</span>
                                        <span class="text-[10px] text-slate-400">Buat subfolder di sini</span>
                                    </div>
                                </button>
                                <div class="h-px bg-slate-100 my-1"></div>
                                <button type="button" onclick="document.getElementById('file-upload-input').click()" 
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-medium text-slate-700 hover:bg-slate-50 transition text-left">
                                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                        <i data-lucide="file-up" class="w-4 h-4"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <span class="block font-semibold text-slate-900">Upload File</span>
                                        <span class="text-[10px] text-slate-400">Pilih berkas dari perangkat</span>
                                    </div>
                                </button>
                                <button type="button" onclick="document.getElementById('folder-upload-input').click()" 
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-medium text-slate-700 hover:bg-slate-50 transition text-left">
                                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                        <i data-lucide="folder-input" class="w-4 h-4"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <span class="block font-semibold text-slate-900">Upload 1 Folder Utuh</span>
                                        <span class="text-[10px] text-slate-400">Hingga 600+ berkas &amp; subfolder</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($currentFolder)
                        <!-- Download Folder as ZIP -->
                        <a href="{{ route('drive.folders.download', $currentFolder->id) }}" 
                           class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 sm:py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium text-xs sm:text-sm transition"
                           title="Unduh seluruh berkas dalam folder ini sebagai file .ZIP">
                            <i data-lucide="file-archive" class="w-4 h-4 text-amber-600"></i>
                            <span class="hidden sm:inline">Unduh Folder</span>
                            <span>(.ZIP)</span>
                        </a>
                    @endif
                </div>

                <!-- Right / Secondary Info on Mobile: View Mode Switcher -->
                <div class="flex items-center gap-2">
                    <!-- Stats Info Pill (Desktop) -->
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-50 border border-slate-200/80 rounded-xl text-[11px] text-slate-500 font-medium">
                        <i data-lucide="database" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>{{ $totalFilesCount }} file ({{ $totalStorageFormatted }})</span>
                    </div>

                    <!-- View Switcher (Grid / List) -->
                    <div class="flex items-center p-1 bg-slate-100 rounded-xl border border-slate-200">
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" 
                           class="p-1.5 rounded-lg text-xs {{ $viewMode === 'grid' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}"
                           title="Tampilan Grid">
                            <i data-lucide="layout-grid" class="w-4 h-4"></i>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" 
                           class="p-1.5 rounded-lg text-xs {{ $viewMode === 'list' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}"
                           title="Tampilan List">
                            <i data-lucide="list" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Middle: Search Input (Full width on mobile, constrained on desktop) -->
            <div class="w-full lg:max-w-md">
                <form method="GET" action="{{ $isReadOnly ? route('pimpinan.drive.index', $currentFolder?->id) : route('admin.drive.index', $currentFolder?->id) }}" class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="q" value="{{ $search ?? '' }}"
                           placeholder="Cari folder atau nama file..."
                           class="w-full pl-10 pr-9 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    @if($search)
                        <a href="{{ $isReadOnly ? route('pimpinan.drive.index', $currentFolder?->id) : route('admin.drive.index', $currentFolder?->id) }}"
                           class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- BREADCRUMBS & NAVIGATION ROW -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 px-1">
        <!-- Breadcrumb Path with horizontal touch scroll -->
        <nav class="flex items-center gap-1.5 text-xs sm:text-sm font-medium text-slate-600 overflow-x-auto py-1 scrollbar-none">
            <a href="{{ $isReadOnly ? route('pimpinan.drive.index') : route('admin.drive.index') }}" 
               class="flex items-center gap-1.5 hover:text-slate-900 transition shrink-0 {{ !$currentFolder ? 'font-bold text-slate-900' : '' }}">
                <i data-lucide="hard-drive" class="w-4 h-4 text-slate-500"></i>
                <span>Drive Berkas</span>
            </a>

            @foreach($breadcrumbs as $ancestor)
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                <a href="{{ $isReadOnly ? route('pimpinan.drive.index', $ancestor->id) : route('admin.drive.index', $ancestor->id) }}" 
                   class="hover:text-slate-900 transition shrink-0 whitespace-nowrap">
                    {{ $ancestor->name }}
                </a>
            @endforeach

            @if($currentFolder)
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                <span class="font-bold text-slate-900 truncate max-w-[200px] sm:max-w-xs shrink-0">{{ $currentFolder->name }}</span>
            @endif
        </nav>

        @if($currentFolder && $currentFolder->parent_id)
            <a href="{{ $isReadOnly ? route('pimpinan.drive.index', $currentFolder->parent_id) : route('admin.drive.index', $currentFolder->parent_id) }}" 
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition shrink-0">
                <i data-lucide="corner-left-up" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Atas</span>
            </a>
        @elseif($currentFolder)
            <a href="{{ $isReadOnly ? route('pimpinan.drive.index') : route('admin.drive.index') }}" 
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition shrink-0">
                <i data-lucide="corner-left-up" class="w-3.5 h-3.5"></i>
                <span>Ke Beranda Drive</span>
            </a>
        @endif
    </div>

    <!-- CROSS-SEARCH: MATCHER DOCUMENTS FROM ARSIP DOKUMEN -->
    @if(!empty($search) && isset($matchedDocuments) && $matchedDocuments->isNotEmpty())
        <div class="bg-white rounded-2xl border border-blue-200/90 shadow-xs overflow-hidden animate-in fade-in duration-200">
            <div class="px-5 py-3.5 bg-gradient-to-r from-blue-500/10 via-blue-500/5 to-transparent border-b border-blue-200/60 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-2xs">
                        <i data-lucide="file-check-2" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 flex items-center gap-2">
                            <span>Ditemukan juga di Arsip Dokumen</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                                {{ $matchedDocuments->count() }} dokumen
                            </span>
                        </h4>
                        <p class="text-[11px] text-slate-500">Arsip surat &amp; dokumen resmi yang cocok dengan kata kunci &ldquo;{{ $search }}&rdquo;</p>
                    </div>
                </div>

                <a href="{{ $isReadOnly ? route('pimpinan.monitoring', ['search' => $search]) : route('admin.documents.index', ['search' => $search]) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-800 text-xs font-semibold transition border border-blue-200 shadow-2xs">
                    <span>Lihat di Arsip Dokumen</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <!-- Matched Documents List -->
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                @foreach($matchedDocuments as $doc)
                    <div class="px-5 py-3 flex items-center justify-between gap-4 hover:bg-blue-50/30 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-xs text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">
                                        {{ $doc->document_number }}
                                    </span>
                                    <span class="font-semibold text-xs sm:text-sm text-slate-900 truncate">
                                        {{ $doc->title }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-1 flex-wrap">
                                    @if($doc->type)
                                        <span class="text-slate-600 font-medium bg-slate-100 px-1.5 py-0.5 rounded">{{ $doc->type }}</span>
                                        <span>•</span>
                                    @endif
                                    @if($doc->document_date)
                                        <span>{{ \Carbon\Carbon::parse($doc->document_date)->translatedFormat('d M Y') }}</span>
                                        <span>•</span>
                                    @endif
                                    <span>{{ $doc->formatted_file_size }}</span>
                                    @if($doc->user)
                                        <span>•</span>
                                        <span>Oleh: {{ $doc->user->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('documents.preview', $doc->id) }}" target="_blank" 
                               title="Pratinjau Dokumen" 
                               class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('documents.download', $doc->id) }}" 
                               title="Unduh Dokumen" 
                               class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                <i data-lucide="download" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- DRAG AND DROP & MOBILE UPLOAD CALLOUT -->
    @if(!$isReadOnly)
        <div id="drop-zone" 
             class="border-2 border-dashed border-slate-300 hover:border-slate-400 bg-slate-50/60 hover:bg-slate-50 rounded-2xl p-4 sm:p-7 text-center transition-all cursor-pointer group">
            <div class="flex flex-col items-center justify-center gap-2 sm:gap-2.5">
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-amber-50 group-hover:bg-amber-100 text-amber-600 flex items-center justify-center transition-transform group-hover:scale-105 shrink-0">
                    <i data-lucide="folder-up" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                </div>
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-800">
                        Tarik &amp; Lepaskan Berkas atau 1 Folder Proyek ke Sini
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-0.5 max-w-md mx-auto">
                        Mendukung hingga ratusan berkas sekaligus (600+ file) dengan antrean upload otomatis yang cepat dan stabil.
                    </p>
                </div>

                <!-- Touch & Click Buttons for Mobile / Tablet / Desktop -->
                <div class="flex items-center justify-center gap-2 pt-1 flex-wrap">
                    <button type="button" onclick="event.stopPropagation(); document.getElementById('file-upload-input').click()" 
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 rounded-xl text-xs font-semibold shadow-2xs transition">
                        <i data-lucide="file-up" class="w-3.5 h-3.5 text-blue-600"></i>
                        <span>Pilih File</span>
                    </button>
                    <button type="button" onclick="event.stopPropagation(); document.getElementById('folder-upload-input').click()" 
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-amber-50 hover:bg-amber-100 border border-amber-200/80 text-amber-800 rounded-xl text-xs font-semibold transition">
                        <i data-lucide="folder-input" class="w-3.5 h-3.5 text-amber-600"></i>
                        <span>Pilih 1 Folder Utuh</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- SECTION 1: FOLDERS -->
    @if($folders->count() > 0)
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="folder" class="w-3.5 h-3.5 text-amber-500"></i>
                    <span>Folder ({{ $folders->count() }})</span>
                </h3>
            </div>

            <!-- FOLDERS RESPONSIVE GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2.5 sm:gap-4">
                @foreach($folders as $f)
                    <div id="folder-card-{{ $f->id }}" class="group relative bg-white hover:bg-slate-50/80 border border-slate-200 rounded-2xl p-3 sm:p-4 transition-all hover:shadow-md flex items-center justify-between gap-3">
                        <a href="{{ $isReadOnly ? route('pimpinan.drive.index', $f->id) : route('admin.drive.index', $f->id) }}" 
                           class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <i data-lucide="folder" class="w-4 h-4 sm:w-5 sm:h-5 fill-amber-100"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-semibold text-slate-900 truncate group-hover:text-amber-700 transition" title="{{ $f->name }}">
                                    {{ $f->name }}
                                </p>
                                <p class="text-[10px] text-slate-400 mt-0.5 truncate">
                                    {{ $f->files_count }} file &bull; {{ $f->children_count }} subfolder
                                </p>
                            </div>
                        </a>

                        <!-- Actions for Folder -->
                        <div class="relative">
                            <button type="button" onclick="toggleFolderMenu(event, 'folder-menu-{{ $f->id }}')" 
                                    class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition"
                                    title="Pilihan Folder">
                                <i data-lucide="more-vertical" class="w-4 h-4"></i>
                            </button>

                            <div id="folder-menu-{{ $f->id }}" 
                                 class="hidden absolute right-0 mt-1 w-44 bg-white rounded-xl shadow-xl border border-slate-100 py-1.5 z-30">
                                <a href="{{ route('drive.folders.download', $f->id) }}" 
                                   class="flex items-center gap-2 px-3 py-1.5 text-xs text-slate-700 hover:bg-slate-50 transition">
                                    <i data-lucide="file-archive" class="w-3.5 h-3.5 text-amber-600"></i>
                                    <span>Unduh (.ZIP)</span>
                                </a>
                                @if(!$isReadOnly)
                                    <button type="button" onclick="openCreateFolderModal({{ $f->id }}, '{{ addslashes($f->name) }}')"
                                            class="w-full flex items-center gap-2 px-3 py-1.5 text-xs text-slate-700 hover:bg-slate-50 transition text-left">
                                        <i data-lucide="folder-plus" class="w-3.5 h-3.5 text-amber-500"></i>
                                        <span>+ Buat Subfolder</span>
                                    </button>
                                    <button type="button" onclick="openRenameFolderModal({{ $f->id }}, '{{ addslashes($f->name) }}')"
                                            class="w-full flex items-center gap-2 px-3 py-1.5 text-xs text-slate-700 hover:bg-slate-50 transition text-left">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-slate-500"></i>
                                        <span>Ganti Nama</span>
                                    </button>
                                    <div class="h-px bg-slate-100 my-1"></div>
                                    <button type="button" onclick="deleteDriveFolder({{ $f->id }}, '{{ addslashes($f->name) }}')"
                                            class="w-full flex items-center gap-2 px-3 py-1.5 text-xs text-rose-600 hover:bg-rose-50 transition text-left cursor-pointer">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Hapus Folder</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- SECTION 2: FILES -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                <i data-lucide="file" class="w-3.5 h-3.5 text-blue-500"></i>
                <span>Berkas File ({{ $files->total() }})</span>
            </h3>

            @if($files->count() > 0)
                <span class="text-[11px] text-slate-400">
                    {{ $files->firstItem() }}-{{ $files->lastItem() }} dari {{ $files->total() }}
                </span>
            @endif
        </div>

        @if($files->count() === 0 && $folders->count() === 0)
            <!-- Empty State -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-12 text-center">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <i data-lucide="folder-open" class="w-7 h-7 sm:w-8 sm:h-8"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Folder Ini Masih Kosong</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1 mb-5">
                    Belum ada berkas di lokasi ini. Gunakan tombol di bawah atau seret folder dari komputer.
                </p>
                @if(!$isReadOnly)
                    <div class="flex items-center justify-center gap-2.5 flex-wrap">
                        <button type="button" onclick="openCreateFolderModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                            Buat Folder
                        </button>
                        <button type="button" onclick="document.getElementById('file-upload-input').click()" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 rounded-xl text-xs font-semibold transition">
                            Upload File
                        </button>
                        <button type="button" onclick="document.getElementById('folder-upload-input').click()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition">
                            Upload Folder
                        </button>
                    </div>
                @endif
            </div>
        @elseif($viewMode === 'grid')
            <!-- FILES RESPONSIVE GRID VIEW (2 cols mobile -> 3 -> 4 -> 5 -> 6 desktop) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2.5 sm:gap-4">
                @foreach($files as $file)
                    <div id="file-card-{{ $file->id }}" class="group relative bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-2.5 sm:p-3.5 transition-all hover:shadow-md flex flex-col justify-between">
                        
                        <!-- File Icon & Thumbnail Preview Area (Click to preview on all screens) -->
                        <div onclick="openPreviewModal('{{ addslashes($file->name) }}', '{{ route('drive.files.preview', $file->id) }}', '{{ $file->is_pdf ? 'pdf' : ($file->is_image ? 'image' : 'other') }}')"
                             class="w-full aspect-square rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center mb-2.5 relative overflow-hidden group-hover:border-slate-200 transition cursor-pointer">
                            @if($file->is_image)
                                <img src="{{ route('drive.files.preview', $file->id) }}" alt="{{ $file->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-slate-400 group-hover:scale-110 transition-transform">
                                    <i data-lucide="{{ $file->icon }}" class="w-8 h-8 sm:w-9 sm:h-9 {{ $file->is_pdf ? 'text-rose-500' : 'text-slate-600' }}"></i>
                                </div>
                            @endif

                            <!-- Quick Preview Overlay for Desktop / Touch -->
                            <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-semibold gap-1 backdrop-blur-2xs">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <span>Preview</span>
                            </div>
                        </div>

                        <!-- File Details -->
                        <div class="min-w-0">
                            <p onclick="openPreviewModal('{{ addslashes($file->name) }}', '{{ route('drive.files.preview', $file->id) }}', '{{ $file->is_pdf ? 'pdf' : ($file->is_image ? 'image' : 'other') }}')"
                               class="text-[11px] sm:text-xs font-semibold text-slate-800 truncate cursor-pointer hover:text-slate-950" title="{{ $file->name }}">
                                {{ $file->name }}
                            </p>
                            <div class="flex items-center justify-between text-[10px] text-slate-400 mt-0.5">
                                <span>{{ $file->formatted_file_size }}</span>
                                <span class="uppercase font-mono text-[9px]">{{ $file->extension ?? 'FILE' }}</span>
                            </div>
                        </div>

                        <!-- Bottom Action Row -->
                        <div class="flex items-center justify-between pt-2 mt-2 border-t border-slate-100 text-slate-400">
                            <a href="{{ route('drive.files.download', $file->id) }}" 
                                class="hover:text-slate-800 transition p-1.5 rounded hover:bg-slate-100" title="Unduh File">
                                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                            </a>

                            @if(!$isReadOnly)
                                <button type="button" onclick="openRenameFileModal({{ $file->id }}, '{{ addslashes($file->name) }}')"
                                        class="hover:text-slate-800 transition p-1.5 rounded hover:bg-slate-100" title="Ganti Nama">
                                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                </button>
                                <button type="button" onclick="deleteDriveFile({{ $file->id }}, '{{ addslashes($file->name) }}')"
                                        class="hover:text-rose-600 transition p-1.5 rounded hover:bg-rose-50 cursor-pointer" title="Hapus Berkas">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- FILES LIST VIEW (Table with horizontal scroll) -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                <th class="py-3 px-3 sm:px-4">Nama File</th>
                                <th class="py-3 px-3 sm:px-4">Ukuran</th>
                                <th class="py-3 px-3 sm:px-4 hidden sm:table-cell">Format</th>
                                <th class="py-3 px-3 sm:px-4 hidden md:table-cell">Waktu</th>
                                <th class="py-3 px-3 sm:px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($files as $file)
                                <tr id="file-row-{{ $file->id }}" class="hover:bg-slate-50/80 transition">
                                    <td class="py-2.5 sm:py-3 px-3 sm:px-4 max-w-[180px] sm:max-w-xs md:max-w-md">
                                        <div class="flex items-center gap-2.5">
                                            <i data-lucide="{{ $file->icon }}" class="w-4 h-4 {{ $file->is_pdf ? 'text-rose-500' : 'text-slate-500' }} shrink-0"></i>
                                            <span onclick="openPreviewModal('{{ addslashes($file->name) }}', '{{ route('drive.files.preview', $file->id) }}', '{{ $file->is_pdf ? 'pdf' : ($file->is_image ? 'image' : 'other') }}')"
                                                  class="font-semibold text-slate-800 truncate block cursor-pointer hover:underline" title="{{ $file->name }}">
                                                {{ $file->name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-2.5 sm:py-3 px-3 sm:px-4 text-slate-500 whitespace-nowrap">
                                        {{ $file->formatted_file_size }}
                                    </td>
                                    <td class="py-2.5 sm:py-3 px-3 sm:px-4 text-slate-500 uppercase font-mono text-[10px] hidden sm:table-cell">
                                        {{ $file->extension ?? '-' }}
                                    </td>
                                    <td class="py-2.5 sm:py-3 px-3 sm:px-4 text-slate-400 whitespace-nowrap hidden md:table-cell">
                                        {{ $file->created_at->format('d/m/y H:i') }}
                                    </td>
                                    <td class="py-2.5 sm:py-3 px-3 sm:px-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1">
                                            <button type="button" onclick="openPreviewModal('{{ addslashes($file->name) }}', '{{ route('drive.files.preview', $file->id) }}', '{{ $file->is_pdf ? 'pdf' : ($file->is_image ? 'image' : 'other') }}')"
                                                    class="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition" title="Preview">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </button>
                                            <a href="{{ route('drive.files.download', $file->id) }}" 
                                               class="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition" title="Unduh">
                                                <i data-lucide="download" class="w-4 h-4"></i>
                                            </a>
                                            @if(!$isReadOnly)
                                                <button type="button" onclick="openRenameFileModal({{ $file->id }}, '{{ addslashes($file->name) }}')"
                                                        class="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition" title="Ganti Nama">
                                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                                </button>
                                                <button type="button" onclick="deleteDriveFile({{ $file->id }}, '{{ addslashes($file->name) }}')"
                                                        class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Hapus">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Pagination -->
        @if($files->hasPages())
            <div class="pt-2 overflow-x-auto">
                {{ $files->links() }}
            </div>
        @endif
    </div>

</div>

<!-- HIDDEN FILE INPUTS FOR UPLOAD -->
<input type="file" id="file-upload-input" multiple class="hidden" onchange="handleFileSelect(event)">
<input type="file" id="folder-upload-input" webkitdirectory directory multiple class="hidden" onchange="handleFolderSelect(event)">

<!-- FLOATING UPLOAD DRAWER (GOOGLE DRIVE STYLE - FULLY MOBILE RESPONSIVE & REALTIME) -->
<div id="upload-progress-drawer" 
     class="hidden fixed bottom-3 sm:bottom-5 right-3 sm:right-5 left-3 sm:left-auto z-50 sm:w-96 max-w-[calc(100vw-1.5rem)] bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transition-all duration-300">
    <!-- Header -->
    <div id="upload-drawer-header" class="px-3.5 sm:px-4 py-2.5 sm:py-3 bg-slate-900 text-white flex items-center justify-between">
        <div class="flex items-center gap-2 min-w-0">
            <div id="upload-header-spinner" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin shrink-0"></div>
            <i id="upload-header-check" data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400 shrink-0 hidden"></i>
            <span id="upload-drawer-title" class="text-xs font-bold truncate">Mengunggah berkas...</span>
        </div>
        <div class="flex items-center gap-1.5 text-slate-300 shrink-0">
            <button id="upload-cancel-btn" type="button" onclick="cancelUploads()" class="px-2 py-0.5 rounded bg-rose-600/80 hover:bg-rose-600 text-white text-[10px] font-bold transition flex items-center gap-1 cursor-pointer" title="Batalkan Semua Unggahan">
                <span>Batal</span>
            </button>
            <button type="button" onclick="toggleMinimizeDrawer()" class="p-1 hover:text-white rounded transition" title="Minimize / Maximize">
                <i data-lucide="minus" class="w-3.5 h-3.5"></i>
            </button>
            <button type="button" onclick="closeUploadDrawer()" class="p-1 hover:text-white rounded transition" title="Tutup">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
            </button>
        </div>
    </div>

    <!-- Body -->
    <div id="upload-drawer-body" class="p-3.5 sm:p-4 space-y-3 bg-white">
        <!-- Progress Bar Summary -->
        <div>
            <div class="flex items-center justify-between text-xs font-semibold mb-1">
                <span id="upload-drawer-count" class="text-slate-700">0 dari 0 selesai</span>
                <span id="upload-drawer-percentage" class="text-slate-900 font-bold">0%</span>
            </div>
            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                <div id="upload-drawer-bar" class="h-full bg-emerald-500 rounded-full transition-all duration-150" style="width: 0%"></div>
            </div>
            <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1">
                <span id="upload-speed-text">Menyiapkan berkas...</span>
                <span id="upload-eta-text"></span>
            </div>
        </div>

        <!-- Completion Action Button -->
        <div id="upload-completion-box" class="hidden">
            <button type="button" onclick="window.location.reload()" 
                    class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl flex items-center justify-center gap-1.5 shadow-sm transition">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                <span id="upload-reload-countdown-btn">Segarkan Halaman Sekarang</span>
            </button>
        </div>

        <!-- Real-time File Items Scroll List -->
        <div id="upload-items-list" class="max-h-44 sm:max-h-56 overflow-y-auto space-y-1.5 pr-1 text-xs">
            <!-- Dynamic items rendered here -->
        </div>
    </div>
</div>

<!-- MODAL: BUAT FOLDER BARU -->
<div id="create-folder-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/50 backdrop-blur-xs">
    <div class="bg-white rounded-2xl max-w-sm w-full p-4 sm:p-5 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="folder-plus" class="w-4 h-4 text-amber-500"></i>
                <span id="create-folder-modal-title">Buat Folder Baru</span>
            </h3>
            <button type="button" onclick="closeCreateFolderModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.drive.folders.store') }}" class="space-y-3">
            @csrf
            <input type="hidden" id="create-folder-parent-id" name="parent_id" value="{{ $currentFolder?->id }}">
            
            <!-- Lokasi Folder Induk / Target -->
            <div class="text-xs text-slate-600 bg-amber-50/70 border border-amber-200/60 px-3 py-2 rounded-xl flex items-center gap-2">
                <i data-lucide="folder-tree" class="w-3.5 h-3.5 text-amber-600 shrink-0"></i>
                <span id="create-folder-location-text" class="truncate font-medium">
                    Lokasi: {{ $currentFolder ? 'Di dalam folder \'' . $currentFolder->name . '\'' : 'Direktori Utama (Beranda Drive)' }}
                </span>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Nama Folder</label>
                <input type="text" id="create-folder-input" name="name" required placeholder="Contoh: berkas"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800">
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeCreateFolderModal()" class="px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                    Buat Folder
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: GANTI NAMA FOLDER -->
<div id="rename-folder-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/50 backdrop-blur-xs">
    <div class="bg-white rounded-2xl max-w-sm w-full p-4 sm:p-5 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4 text-slate-600"></i>
                <span>Ganti Nama Folder</span>
            </h3>
            <button type="button" onclick="closeRenameFolderModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <form id="rename-folder-form" method="POST" action="" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Nama Folder Baru</label>
                <input type="text" id="rename-folder-input" name="name" required
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800">
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeRenameFolderModal()" class="px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: GANTI NAMA FILE -->
<div id="rename-file-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/50 backdrop-blur-xs">
    <div class="bg-white rounded-2xl max-w-sm w-full p-4 sm:p-5 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="edit-2" class="w-4 h-4 text-slate-600"></i>
                <span>Ganti Nama Berkas</span>
            </h3>
            <button type="button" onclick="closeRenameFileModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <form id="rename-file-form" method="POST" action="" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Nama File</label>
                <input type="text" id="rename-file-input" name="name" required
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800">
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeRenameFileModal()" class="px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: QUICK PREVIEW (PDF & IMAGE VIEWER - RESPONSIVE) -->
<div id="preview-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-6 bg-slate-950/80 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-5xl h-[92vh] sm:h-[88vh] flex flex-col shadow-2xl overflow-hidden">
        <!-- Preview Header -->
        <div class="px-3.5 sm:px-5 py-3 sm:py-3.5 bg-slate-900 text-white flex items-center justify-between shrink-0 gap-2">
            <div class="flex items-center gap-2 truncate max-w-[200px] sm:max-w-md">
                <i data-lucide="file-text" class="w-4 h-4 text-amber-400 shrink-0"></i>
                <h3 id="preview-modal-title" class="text-xs sm:text-sm font-semibold truncate">Pratinjau Dokumen</h3>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a id="preview-modal-download-btn" href="#" 
                   class="px-2.5 sm:px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-medium flex items-center gap-1.5 transition">
                    <i data-lucide="download" class="w-3.5 h-3.5"></i>
                    <span class="hidden sm:inline">Unduh</span>
                </a>
                <button type="button" onclick="closePreviewModal()" class="p-1.5 text-slate-400 hover:text-white rounded-lg transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <!-- Preview Content -->
        <div id="preview-modal-body" class="flex-1 bg-slate-100 relative overflow-hidden flex items-center justify-center p-1 sm:p-2">
            <!-- Dynamic Iframe or Image -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Configuration Context
    const CURRENT_FOLDER_ID = @json($currentFolder?->id ?? null);
    const UPLOAD_URL = @json(route('admin.drive.upload'));
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Dropdown toggle
    function toggleNewMenu() {
        const menu = document.getElementById('new-menu-dropdown');
        if (menu) menu.classList.toggle('hidden');
    }

    // Close dropdown when clicked outside
    document.addEventListener('click', function(e) {
        const container = document.getElementById('new-action-dropdown-container');
        if (container && !container.contains(e.target)) {
            const menu = document.getElementById('new-menu-dropdown');
            if (menu) menu.classList.add('hidden');
        }

        // Folder contextual menus
        document.querySelectorAll('[id^="folder-menu-"]').forEach(el => {
            if (!el.parentElement.contains(e.target)) {
                el.classList.add('hidden');
            }
        });
    });

    function toggleFolderMenu(event, id) {
        event.stopPropagation();
        const menu = document.getElementById(id);
        if (menu) menu.classList.toggle('hidden');
    }

    // Modal Create Folder
    function openCreateFolderModal(parentId = null, parentName = null) {
        document.getElementById('new-menu-dropdown')?.classList.add('hidden');
        document.querySelectorAll('[id^="folder-menu-"]').forEach(el => el.classList.add('hidden'));

        const modal = document.getElementById('create-folder-modal');
        const titleEl = document.getElementById('create-folder-modal-title');
        const parentInput = document.getElementById('create-folder-parent-id');
        const locationText = document.getElementById('create-folder-location-text');
        const input = document.getElementById('create-folder-input');

        if (parentId) {
            if (parentInput) parentInput.value = parentId;
            if (titleEl) titleEl.textContent = `Buat Subfolder di '${parentName}'`;
            if (locationText) locationText.textContent = `Lokasi: Di dalam folder '${parentName}'`;
        } else {
            if (parentInput) parentInput.value = CURRENT_FOLDER_ID || '';
            const currentName = @json($currentFolder?->name ?? null);
            if (currentName) {
                if (titleEl) titleEl.textContent = `Buat Subfolder di '${currentName}'`;
                if (locationText) locationText.textContent = `Lokasi: Di dalam folder '${currentName}'`;
            } else {
                if (titleEl) titleEl.textContent = 'Buat Folder Baru';
                if (locationText) locationText.textContent = 'Lokasi: Direktori Utama (Beranda Drive)';
            }
        }

        if (input) {
            input.value = '';
            setTimeout(() => input.focus(), 100);
        }

        modal?.classList.remove('hidden');
        if (window.lucide) lucide.createIcons();
    }
    function closeCreateFolderModal() {
        document.getElementById('create-folder-modal')?.classList.add('hidden');
    }

    // Modal Rename Folder
    function openRenameFolderModal(id, name) {
        document.getElementById('rename-folder-input').value = name;
        document.getElementById('rename-folder-form').action = `/admin/drive/folders/${id}`;
        document.getElementById('rename-folder-modal')?.classList.remove('hidden');
    }
    function closeRenameFolderModal() {
        document.getElementById('rename-folder-modal')?.classList.add('hidden');
    }

    // Modal Rename File
    function openRenameFileModal(id, name) {
        document.getElementById('rename-file-input').value = name;
        document.getElementById('rename-file-form').action = `/admin/drive/files/${id}`;
        document.getElementById('rename-file-modal')?.classList.remove('hidden');
    }
    function closeRenameFileModal() {
        document.getElementById('rename-file-modal')?.classList.add('hidden');
    }

    // Seamless Async Delete Folder (in-page dissolution without navigation)
    function deleteDriveFolder(folderId, folderName) {
        if (!confirm(`Hapus folder '${folderName}' beserta seluruh isinya?`)) return;

        fetch(`/admin/drive/folders/${folderId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json().catch(() => ({ success: true }));
        })
        .then(() => {
            const el = document.getElementById(`folder-card-${folderId}`);
            if (el) {
                el.style.transition = 'all 0.3s ease';
                el.style.opacity = '0';
                el.style.transform = 'scale(0.9)';
                setTimeout(() => el.remove(), 300);
            } else {
                window.location.reload();
            }
        })
        .catch(err => {
            console.error('Delete folder error:', err);
            window.location.reload();
        });
    }

    // Seamless Async Delete File (in-page dissolution without navigation)
    function deleteDriveFile(fileId, fileName) {
        if (!confirm(`Hapus berkas '${fileName}'?`)) return;

        fetch(`/admin/drive/files/${fileId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json().catch(() => ({ success: true }));
        })
        .then(() => {
            const cardEl = document.getElementById(`file-card-${fileId}`);
            const rowEl = document.getElementById(`file-row-${fileId}`);
            const target = cardEl || rowEl;
            if (target) {
                target.style.transition = 'all 0.3s ease';
                target.style.opacity = '0';
                target.style.transform = 'scale(0.9)';
                setTimeout(() => target.remove(), 300);
            } else {
                window.location.reload();
            }
        })
        .catch(err => {
            console.error('Delete file error:', err);
            window.location.reload();
        });
    }

    // Quick Preview Modal
    function openPreviewModal(title, url, type) {
        const modal = document.getElementById('preview-modal');
        const titleEl = document.getElementById('preview-modal-title');
        const downloadBtn = document.getElementById('preview-modal-download-btn');
        const bodyEl = document.getElementById('preview-modal-body');

        titleEl.textContent = title;
        downloadBtn.href = url.replace('/preview', '/download');

        if (type === 'image') {
            bodyEl.innerHTML = `<img src="${url}" class="max-w-full max-h-full object-contain rounded-lg shadow-sm" alt="${title}">`;
        } else {
            bodyEl.innerHTML = `<iframe src="${url}" class="w-full h-full rounded-lg border-0 bg-white" title="${title}"></iframe>`;
        }

        modal.classList.remove('hidden');
    }
    function closePreviewModal() {
        const modal = document.getElementById('preview-modal');
        const bodyEl = document.getElementById('preview-modal-body');
        modal.classList.add('hidden');
        bodyEl.innerHTML = ''; // reset iframe
    }

    // ===============================================================
    // HIGH-PERFORMANCE STREAMING BATCH UPLOADER (GOOGLE DRIVE ENGINE)
    // ===============================================================
    let uploadQueue = [];
    let isScanning = false;
    let isCancelled = false;
    let completedCount = 0;
    let totalCount = 0;
    let activeBatchRequests = 0;
    let uploadStartTime = null;
    let reloadTimer = null;

    const BATCH_SIZE = 12; // 12 files sent per request for 12x speedup
    const MAX_CONCURRENT_BATCHES = 3; // 3 parallel batch connections

    // Filter out heavy system/development folders that can cause 10,000+ unnecessary files
    const IGNORED_DIRS = new Set([
        'node_modules', 'vendor', '.git', '.github', '.idea', '.vscode', '__MACOSX', '.svn', '.cache', 'temp', 'tmp'
    ]);
    const IGNORED_FILES = new Set([
        '.DS_Store', 'Thumbs.db', 'desktop.ini', '.env', '.env.example', '.gitattributes', '.gitignore', '.editorconfig'
    ]);

    function isPathOrNameIgnored(pathOrName) {
        if (!pathOrName) return false;
        const normalized = pathOrName.replace(/\\/g, '/');
        const segments = normalized.split('/');
        
        // Check directory segments
        for (let i = 0; i < segments.length - 1; i++) {
            const seg = segments[i].toLowerCase();
            if (IGNORED_DIRS.has(seg) || (seg.startsWith('.') && seg.length > 1)) {
                return true;
            }
        }

        // Check file name
        const fileName = segments[segments.length - 1];
        if (IGNORED_FILES.has(fileName)) return true;

        return false;
    }

    function handleFileSelect(event) {
        const files = Array.from(event.target.files);
        if (files.length > 0) {
            enqueueFileListDirectly(files);
        }
        event.target.value = '';
    }

    function handleFolderSelect(event) {
        const files = Array.from(event.target.files);
        if (files.length > 0) {
            enqueueFileListDirectly(files);
        }
        event.target.value = '';
    }

    // Drag and Drop Zone
    const dropZone = document.getElementById('drop-zone');
    if (dropZone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            window.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.add('border-slate-800', 'bg-slate-100');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            window.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-slate-800', 'bg-slate-100');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            const items = e.dataTransfer.items;
            if (items && items.length > 0) {
                traverseDataTransferItems(items);
            } else if (e.dataTransfer.files) {
                enqueueFileListDirectly(Array.from(e.dataTransfer.files));
            }
        });

        dropZone.addEventListener('click', (e) => {
            if (e.target.closest('button')) return;
            document.getElementById('file-upload-input').click();
        });
    }

    // Direct File List Enqueue
    function enqueueFileListDirectly(files) {
        openUploadDrawer();
        let skippedCount = 0;
        files.forEach(file => {
            const fullPath = file.webkitRelativePath || file.name;
            if (isPathOrNameIgnored(fullPath)) {
                skippedCount++;
                return;
            }
            enqueueSingleFile(file);
        });

        if (skippedCount > 0) {
            console.info(`[Drive] Melewati ${skippedCount} file sistem/kodingan yang tidak perlu.`);
        }
        checkCompletion();
    }

    // Asynchronous & Robust Folder Scanner (Reads all Chromium 100-batch entries completely)
    async function traverseDataTransferItems(items) {
        openUploadDrawer();
        isScanning = true;
        updateDrawerSummary();

        const entries = [];
        for (let i = 0; i < items.length; i++) {
            const entry = items[i].webkitGetAsEntry ? items[i].webkitGetAsEntry() : null;
            if (entry) {
                entries.push(entry);
            }
        }

        for (const entry of entries) {
            if (isCancelled) break;
            await scanEntryRecursive(entry, '');
        }

        isScanning = false;
        updateDrawerSummary();
        checkCompletion();
    }

    async function readAllEntriesFromReader(dirReader) {
        const allEntries = [];
        while (true) {
            if (isCancelled) break;
            const entries = await new Promise((resolve) => {
                dirReader.readEntries(resolve, () => resolve([]));
            });
            if (!entries || entries.length === 0) break;
            allEntries.push(...entries);
        }
        return allEntries;
    }

    async function scanEntryRecursive(entry, path = '') {
        if (isCancelled) return;

        if (entry.isFile) {
            const relativePath = path ? `${path}/${entry.name}` : entry.name;
            if (isPathOrNameIgnored(relativePath)) return;

            const file = await new Promise(resolve => entry.file(resolve, () => resolve(null)));
            if (file) {
                file.customRelativePath = relativePath;
                enqueueSingleFile(file);
            }
        } else if (entry.isDirectory) {
            const dirNameLower = entry.name.toLowerCase();
            if (IGNORED_DIRS.has(dirNameLower) || (dirNameLower.startsWith('.') && dirNameLower.length > 1)) {
                return; // Lewati folder kodingan seperti vendor, node_modules, .git
            }

            const dirReader = entry.createReader();
            const entries = await readAllEntriesFromReader(dirReader);
            const nextPath = path ? `${path}/${entry.name}` : entry.name;
            for (const child of entries) {
                if (isCancelled) break;
                await scanEntryRecursive(child, nextPath);
            }
        }
    }

    function openUploadDrawer() {
        isCancelled = false;
        if (reloadTimer) clearInterval(reloadTimer);

        const drawer = document.getElementById('upload-progress-drawer');
        const body = document.getElementById('upload-drawer-body');
        const cancelBtn = document.getElementById('upload-cancel-btn');
        const spinner = document.getElementById('upload-header-spinner');
        const checkIcon = document.getElementById('upload-header-check');
        const completionBox = document.getElementById('upload-completion-box');

        drawer.classList.remove('hidden');
        body.classList.remove('hidden');
        if (cancelBtn) cancelBtn.classList.remove('hidden');
        if (spinner) spinner.classList.remove('hidden');
        if (checkIcon) checkIcon.classList.add('hidden');
        if (completionBox) completionBox.classList.add('hidden');
    }

    function enqueueSingleFile(file) {
        if (isCancelled) return;

        totalCount++;
        const fileItem = {
            id: 'file-' + Math.random().toString(36).substr(2, 9),
            file: file,
            status: 'pending',
            relativePath: file.webkitRelativePath || file.customRelativePath || file.name
        };

        uploadQueue.push(fileItem);

        // Keep DOM lightweight by displaying maximum 40 latest items
        const list = document.getElementById('upload-items-list');
        if (list.children.length < 40) {
            const div = document.createElement('div');
            div.id = fileItem.id;
            div.className = 'flex items-center justify-between gap-2 p-2 bg-slate-50 rounded-xl text-slate-700';
            div.innerHTML = `
                <div class="min-w-0 flex-1 truncate">
                    <p class="font-semibold text-slate-800 truncate">${escapeHtml(file.name)}</p>
                    <p class="text-[10px] text-slate-400 truncate">${formatBytes(file.size)}</p>
                </div>
                <div class="status-icon text-slate-400 shrink-0">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                </div>
            `;
            list.appendChild(div);
            if (window.lucide) lucide.createIcons();
        }

        updateDrawerSummary();
        pumpBatchQueue();
    }

    function pumpBatchQueue() {
        if (isCancelled) return;

        while (activeBatchRequests < MAX_CONCURRENT_BATCHES && uploadQueue.length > 0) {
            const batch = uploadQueue.splice(0, BATCH_SIZE);
            if (batch.length === 0) break;

            activeBatchRequests++;
            if (!uploadStartTime) uploadStartTime = Date.now();
            sendBatchRequest(batch);
        }

        checkCompletion();
    }

    function sendBatchRequest(batch) {
        batch.forEach(item => {
            updateItemStatus(item.id, 'uploading');
        });

        const formData = new FormData();
        if (CURRENT_FOLDER_ID) {
            formData.append('folder_id', CURRENT_FOLDER_ID);
        }

        batch.forEach(item => {
            formData.append('files[]', item.file);
            formData.append('relative_paths[]', item.relativePath || item.file.name);
        });

        fetch(UPLOAD_URL, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(data => {
            if (isCancelled) return;
            completedCount += batch.length;
            batch.forEach(item => {
                updateItemStatus(item.id, 'done');
            });
        })
        .catch(err => {
            console.error('Batch upload error:', err);
            // In case of error, still advance count to prevent infinite block
            completedCount += batch.length;
            batch.forEach(item => {
                updateItemStatus(item.id, 'error');
            });
        })
        .finally(() => {
            activeBatchRequests--;
            updateDrawerSummary();
            pumpBatchQueue();
        });
    }

    function updateItemStatus(itemId, status) {
        const el = document.getElementById(itemId);
        if (!el) return;

        const iconContainer = el.querySelector('.status-icon');
        if (!iconContainer) return;

        if (status === 'uploading') {
            iconContainer.innerHTML = `<div class="w-3.5 h-3.5 border-2 border-emerald-500/30 border-t-emerald-600 rounded-full animate-spin"></div>`;
        } else if (status === 'done') {
            iconContainer.innerHTML = `<i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>`;
            if (window.lucide) lucide.createIcons();
        } else if (status === 'error') {
            iconContainer.innerHTML = `<i data-lucide="alert-circle" class="w-4 h-4 text-rose-500"></i>`;
            if (window.lucide) lucide.createIcons();
        }
    }

    function updateDrawerSummary() {
        const rawPercentage = totalCount > 0 ? (completedCount / totalCount) * 100 : 0;
        
        // Format percentage with decimals if small to show continuous progress without being stuck at 0%
        let percentageDisplay = '0%';
        if (totalCount > 0) {
            if (completedCount >= totalCount) {
                percentageDisplay = '100%';
            } else if (rawPercentage < 10 && rawPercentage > 0) {
                percentageDisplay = rawPercentage.toFixed(1) + '%';
            } else {
                percentageDisplay = Math.min(99, Math.floor(rawPercentage)) + '%';
            }
        }

        const barWidth = Math.min(100, rawPercentage);

        document.getElementById('upload-drawer-percentage').textContent = percentageDisplay;
        document.getElementById('upload-drawer-count').textContent = `${completedCount} dari ${totalCount} selesai`;
        document.getElementById('upload-drawer-bar').style.width = barWidth + '%';

        if (isScanning && uploadQueue.length > 0) {
            document.getElementById('upload-drawer-title').textContent = `Memindai & Mengunggah... (${percentageDisplay})`;
        } else {
            document.getElementById('upload-drawer-title').textContent = `Mengunggah ${totalCount} berkas... (${percentageDisplay})`;
        }

        // Calculate upload speed and ETA
        if (uploadStartTime && completedCount > 0) {
            const elapsedSec = (Date.now() - uploadStartTime) / 1000;
            if (elapsedSec > 0.5) {
                const speed = Math.round(completedCount / elapsedSec);
                const speedTextEl = document.getElementById('upload-speed-text');
                if (speedTextEl) {
                    speedTextEl.textContent = `${speed} berkas/detik`;
                }

                const remaining = totalCount - completedCount;
                if (speed > 0 && remaining > 0) {
                    const etaSec = Math.ceil(remaining / speed);
                    const etaTextEl = document.getElementById('upload-eta-text');
                    if (etaTextEl) {
                        etaTextEl.textContent = etaSec > 60 ? `Sisa ~${Math.ceil(etaSec / 60)} menit` : `Sisa ~${etaSec} detik`;
                    }
                }
            }
        }
    }

    function checkCompletion() {
        if (!isScanning && activeBatchRequests === 0 && uploadQueue.length === 0 && totalCount > 0) {
            onAllUploadsFinished();
        }
    }

    function onAllUploadsFinished() {
        const spinner = document.getElementById('upload-header-spinner');
        const checkIcon = document.getElementById('upload-header-check');
        const cancelBtn = document.getElementById('upload-cancel-btn');
        const completionBox = document.getElementById('upload-completion-box');

        if (spinner) spinner.classList.add('hidden');
        if (checkIcon) checkIcon.classList.remove('hidden');
        if (cancelBtn) cancelBtn.classList.add('hidden');
        if (completionBox) completionBox.classList.remove('hidden');

        document.getElementById('upload-drawer-title').textContent = `Selesai! (${completedCount} berkas terunggah)`;
        document.getElementById('upload-drawer-percentage').textContent = '100%';
        document.getElementById('upload-drawer-bar').style.width = '100%';
        document.getElementById('upload-speed-text').textContent = 'Semua berkas berhasil disimpan di server.';
        document.getElementById('upload-eta-text').textContent = '';

        if (window.lucide) lucide.createIcons();

        // Safe 3-second countdown timer before automatic page reload
        let secondsLeft = 3;
        const countdownBtn = document.getElementById('upload-reload-countdown-btn');
        reloadTimer = setInterval(() => {
            secondsLeft--;
            if (countdownBtn) {
                countdownBtn.textContent = `Segarkan Halaman (${secondsLeft}s)...`;
            }
            if (secondsLeft <= 0) {
                clearInterval(reloadTimer);
                window.location.reload();
            }
        }, 1000);
    }

    function cancelUploads() {
        if (!confirm('Apakah Anda yakin ingin membatalkan sisa antrean unggahan?')) return;
        isCancelled = true;
        isScanning = false;
        uploadQueue = [];
        if (reloadTimer) clearInterval(reloadTimer);

        document.getElementById('upload-drawer-title').textContent = `Dibatalkan (${completedCount} berkas tersimpan)`;
        const spinner = document.getElementById('upload-header-spinner');
        if (spinner) spinner.classList.add('hidden');
        const cancelBtn = document.getElementById('upload-cancel-btn');
        if (cancelBtn) cancelBtn.classList.add('hidden');
        document.getElementById('upload-speed-text').textContent = 'Unggahan dihentikan.';
        document.getElementById('upload-eta-text').textContent = '';
    }

    function toggleMinimizeDrawer() {
        const body = document.getElementById('upload-drawer-body');
        body.classList.toggle('hidden');
    }

    function closeUploadDrawer() {
        document.getElementById('upload-progress-drawer').classList.add('hidden');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatBytes(bytes) {
        if (!bytes || bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }
</script>
@endpush
@endsection
