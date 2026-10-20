@extends('layouts.app')

@section('title', 'Monitoring Arsip Eksekutif')
@section('page_title', 'Ruang Pemantauan Arsip Global')
@section('page_subtitle', 'Akses transparan seluruh repositori kearsipan daerah secara read-only')

@section('content')
<div class="space-y-5 sm:space-y-6">
    <!-- Notice Banner (Read-only reminder) -->
    <div class="bg-slate-100 border border-slate-200 rounded-2xl p-4 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                <i data-lucide="shield" class="w-4 h-4"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-900">Hak Akses: Read-Only Executive Monitoring</p>
                <p class="text-[11px] text-slate-500">
                    Anda memiliki hak penuh untuk meninjau pratinjau PDF dan mengunduh arsip dokumen tanpa kewenangan memodifikasi data.
                </p>
            </div>
        </div>
        <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-white text-slate-700 border border-slate-200 shrink-0">
            Terverifikasi Sistem
        </span>
    </div>

    <!-- Filter & Search Toolbar Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5">
        <form method="GET" action="{{ route('pimpinan.monitoring') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- 1. Search Query -->
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pencarian Cepat</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nomor dokumen, perihal, atau deskripsi..."
                               class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    </div>
                </div>

                <!-- 2. Filter Kategori -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kategori Dokumen</label>
                    <select name="type" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                        <option value="">Semua Kategori</option>
                        <option value="Surat Masuk" {{ request('type') === 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                        <option value="Surat Keluar" {{ request('type') === 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                        <option value="Berkas" {{ request('type') === 'Berkas' ? 'selected' : '' }}>Berkas</option>
                        <option value="Laporan BPN" {{ request('type') === 'Laporan BPN' ? 'selected' : '' }}>Laporan BPN</option>
                        <option value="Laporan Dispenda" {{ request('type') === 'Laporan Dispenda' ? 'selected' : '' }}>Laporan Dispenda</option>
                    </select>
                </div>

                <!-- 3. Dari Tanggal -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                </div>

                <!-- 4. Sampai Tanggal -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pt-2 border-t border-slate-100 text-xs">
                <span class="text-slate-500">
                    Total <strong>{{ $documents->total() }}</strong> arsip dokumen terpantau
                </span>
                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    @if(request()->hasAny(['search', 'type', 'start_date', 'end_date']))
                        <a href="{{ route('pimpinan.monitoring') }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition font-medium">
                            Reset Filter
                        </a>
                    @endif
                    <button type="submit" class="px-4 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-lg font-semibold transition flex items-center gap-1.5">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        <span>Saring Data</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if(!empty($search) && ((isset($matchedDriveFiles) && $matchedDriveFiles->isNotEmpty()) || (isset($matchedDriveFolders) && $matchedDriveFolders->isNotEmpty())))
        <!-- Cross-Search: Matched Drive Results -->
        <div class="bg-white rounded-2xl border border-amber-200/90 shadow-xs overflow-hidden animate-in fade-in duration-200">
            <div class="px-5 py-3.5 bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border-b border-amber-200/60 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-2xs">
                        <i data-lucide="hard-drive" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 flex items-center gap-2">
                            <span>Ditemukan juga di Drive Berkas</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                {{ $matchedDriveFiles->count() }} berkas @if(isset($matchedDriveFolders) && $matchedDriveFolders->isNotEmpty()), {{ $matchedDriveFolders->count() }} folder @endif
                            </span>
                        </h4>
                        <p class="text-[11px] text-slate-500">Berkas/folder yang tersimpan di Drive dan cocok dengan kata kunci &ldquo;{{ $search }}&rdquo;</p>
                    </div>
                </div>

                <a href="{{ route('pimpinan.drive.index', ['q' => $search]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-100 hover:bg-amber-200 text-amber-900 text-xs font-semibold transition border border-amber-300/60 shadow-2xs">
                    <span>Buka di Drive Pimpinan</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <!-- Matched Folders Chips -->
            @if(isset($matchedDriveFolders) && $matchedDriveFolders->isNotEmpty())
                <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2 flex-wrap">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mr-1">Folder Terkait:</span>
                    @foreach($matchedDriveFolders as $df)
                        <a href="{{ route('pimpinan.drive.index', $df->id) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 bg-white hover:bg-amber-50 border border-slate-200 hover:border-amber-300 rounded-xl text-xs text-slate-700 transition shadow-2xs">
                            <i data-lucide="folder" class="w-3.5 h-3.5 text-amber-500"></i>
                            <span class="font-medium">{{ $df->name }}</span>
                            <span class="text-[10px] text-slate-400">({{ $df->files_count }} file)</span>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Matched Files List -->
            @if(isset($matchedDriveFiles) && $matchedDriveFiles->isNotEmpty())
                <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
                    @foreach($matchedDriveFiles as $file)
                        <div class="px-5 py-3 flex items-center justify-between gap-4 hover:bg-amber-50/30 transition">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="file-text" class="w-4 h-4 text-slate-500"></i>
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('drive.files.preview', $file->id) }}" target="_blank" class="font-semibold text-xs sm:text-sm text-slate-900 hover:text-amber-700 truncate block">
                                        {{ $file->name }}
                                    </a>
                                    <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                                        @if($file->folder)
                                            <a href="{{ route('pimpinan.drive.index', $file->folder_id) }}" class="hover:text-amber-700 hover:underline flex items-center gap-1">
                                                <i data-lucide="folder" class="w-3 h-3 text-amber-500"></i>
                                                <span>{{ $file->folder->name }}</span>
                                            </a>
                                            <span>•</span>
                                        @endif
                                        <span>{{ $file->formatted_file_size }}</span>
                                        <span>•</span>
                                        <span>{{ $file->created_at?->translatedFormat('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('drive.files.preview', $file->id) }}" target="_blank" 
                                   title="Preview Berkas" 
                                   class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('drive.files.download', $file->id) }}" 
                                   title="Unduh Berkas" 
                                   class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- Monitoring Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="table-responsive-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="py-3 px-4 whitespace-nowrap">Nomor Surat/Dokumen</th>
                        <th class="py-3 px-4 whitespace-nowrap">Tanggal Dokumen</th>
                        <th class="py-3 px-4">Perihal</th>
                        <th class="py-3 px-4 whitespace-nowrap">Klasifikasi</th>
                        <th class="py-3 px-4 whitespace-nowrap">Pengarsip</th>
                        <th class="py-3 px-4 text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-semibold text-slate-900 whitespace-nowrap">
                                <span class="font-mono bg-slate-100 border border-slate-200/70 px-2 py-0.5 rounded text-slate-700 text-[11px]">{{ $doc->document_number ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($doc->document_date)->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-3 px-4 min-w-[200px] max-w-sm">
                                <div class="font-semibold text-slate-900 leading-snug">{{ $doc->title }}</div>
                                @if($doc->description)
                                    <div class="text-[11px] text-slate-400 leading-tight mt-0.5 line-clamp-2">{{ $doc->description }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($doc->type === 'Surat Masuk')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-sky-50 text-sky-700 border border-sky-200">
                                        Surat Masuk
                                    </span>
                                @elseif($doc->type === 'Surat Keluar')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        Surat Keluar
                                    </span>
                                @elseif($doc->type === 'Berkas')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Berkas
                                    </span>
                                @elseif($doc->type === 'Laporan')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-teal-50 text-teal-700 border border-teal-200">
                                        {{ $doc->sub_type ?? 'Laporan' }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center text-[10px] font-bold">
                                        {{ strtoupper(substr($doc->user->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <span class="truncate max-w-[100px] font-medium">{{ $doc->user->name ?? 'Admin' }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center p-1 bg-slate-100/70 border border-slate-200/80 rounded-xl gap-1">
                                    <!-- Detail Trigger -->
                                    <a href="{{ route('documents.show', $doc) }}" 
                                       class="w-7 h-7 flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg transition"
                                       title="Detail Dokumen">
                                        <i data-lucide="info" class="w-3.5 h-3.5"></i>
                                    </a>

                                    <!-- Preview Button -->
                                    <button type="button" 
                                            onclick="openPdfModal('{{ route('documents.preview', $doc) }}', '{{ addslashes($doc->title) }}', '{{ route('documents.download', $doc) }}')"
                                            class="w-7 h-7 flex items-center justify-center text-sky-600 hover:text-sky-800 hover:bg-white rounded-lg transition" 
                                            title="Pratinjau PDF">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    </button>

                                    <!-- Download Button -->
                                    <a href="{{ route('documents.download', $doc) }}" 
                                       class="w-7 h-7 flex items-center justify-center text-emerald-600 hover:text-emerald-800 hover:bg-white rounded-lg transition" 
                                       title="Unduh Berkas PDF">
                                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto">
                                    <i data-lucide="folder-search" class="w-10 h-10 text-slate-300 mx-auto mb-2"></i>
                                    <p class="font-semibold text-slate-700">Tidak ada arsip yang cocok</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
