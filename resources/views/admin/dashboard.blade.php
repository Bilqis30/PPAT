@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Pengelola Arsip')
@section('page_subtitle', 'Ringkasan statistik kearsipan dokumen dan aktivitas terbaru')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <!-- Welcome Greeting Card -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-2xl p-5 sm:p-8 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-5 sm:gap-6">
        <div>
            
            <h2 class="text-lg sm:text-2xl font-bold tracking-tight">Halo, {{ Auth::user()->name }}</h2>
            <p class="text-slate-300 text-xs sm:text-sm mt-1 max-w-xl">
                Kelola arsip surat dan laporan dinas dengan aman, terstruktur, dan tervalidasi langsung di penyimpanan lokal.
            </p>
        </div>
        
    </div>

    <!-- 6 Statistics Cards (Clean Modern White & Gray) -->
    <div>
        <div class="flex items-center justify-between mb-3.5 sm:mb-4">
            <h3 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wider">Ringkasan Statistik Dokumen</h3>
            <span class="text-[11px] sm:text-xs text-slate-400">Pembaruan realtime</span>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4">
            <!-- 1. Total Dokumen -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm hover:border-slate-300 transition group card-hover">
                <div class="flex items-center justify-between mb-2.5 sm:mb-3">
                    <span class="text-xs font-semibold text-slate-500">Total Arsip</span>
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition">
                        <i data-lucide="files" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ number_format($stats['total']) }}</div>
                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-1">Semua kategori</p>
            </div>

            <!-- 2. Surat Masuk -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm hover:border-slate-300 transition group card-hover">
                <div class="flex items-center justify-between mb-2.5 sm:mb-3">
                    <span class="text-xs font-semibold text-slate-500">Surat Masuk</span>
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center group-hover:bg-sky-600 group-hover:text-white transition">
                        <i data-lucide="inbox" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ number_format($stats['surat_masuk']) }}</div>
                <p class="text-[10px] sm:text-[11px] text-sky-600 font-medium mt-1">Surat masuk</p>
            </div>

            <!-- 3. Surat Keluar -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm hover:border-slate-300 transition group card-hover">
                <div class="flex items-center justify-between mb-2.5 sm:mb-3">
                    <span class="text-xs font-semibold text-slate-500">Surat Keluar</span>
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition">
                        <i data-lucide="send" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ number_format($stats['surat_keluar']) }}</div>
                <p class="text-[10px] sm:text-[11px] text-indigo-600 font-medium mt-1">Surat keluar</p>
            </div>

            <!-- 4. Berkas -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm hover:border-slate-300 transition group card-hover">
                <div class="flex items-center justify-between mb-2.5 sm:mb-3">
                    <span class="text-xs font-semibold text-slate-500">Berkas</span>
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition">
                        <i data-lucide="folder" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ number_format($stats['berkas']) }}</div>
                <p class="text-[10px] sm:text-[11px] text-amber-600 font-medium mt-1">Berkas umum</p>
            </div>

            <!-- 5. Laporan BPN -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm hover:border-slate-300 transition group card-hover">
                <div class="flex items-center justify-between mb-2.5 sm:mb-3">
                    <span class="text-xs font-semibold text-slate-500">Laporan BPN</span>
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition">
                        <i data-lucide="landmark" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ number_format($stats['laporan_bpn']) }}</div>
                <p class="text-[10px] sm:text-[11px] text-teal-600 font-medium mt-1">Pertanahan</p>
            </div>

            <!-- 6. Laporan Dispenda -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm hover:border-slate-300 transition group card-hover">
                <div class="flex items-center justify-between mb-2.5 sm:mb-3">
                    <span class="text-xs font-semibold text-slate-500">Dispenda</span>
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center group-hover:bg-violet-600 group-hover:text-white transition">
                        <i data-lucide="receipt" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ number_format($stats['laporan_dispenda']) }}</div>
                <p class="text-[10px] sm:text-[11px] text-violet-600 font-medium mt-1">Pajak daerah</p>
            </div>
        </div>
    </div>

    <!-- Recent Documents Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">Aktivitas Dokumen Terbaru</h3>
                <p class="text-xs text-slate-400 mt-0.5">Daftar berkas yang baru saja diunggah ke repositori</p>
            </div>
            <a href="{{ route('admin.documents.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1">
                <span class="hidden sm:inline">Lihat Semua Arsip</span>
                <span class="sm:hidden">Semua</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="table-responsive-container">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="py-3 px-4 whitespace-nowrap">Nomor Dokumen</th>
                        <th class="py-3 px-4 whitespace-nowrap">Tanggal</th>
                        <th class="py-3 px-4">Perihal</th>
                        <th class="py-3 px-4 whitespace-nowrap">Tipe Dokumen</th>
                        <th class="py-3 px-4 whitespace-nowrap">Ukuran</th>
                        <th class="py-3 px-4 text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($recentDocuments as $doc)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-semibold text-slate-900 whitespace-nowrap">
                                <span class="font-mono bg-slate-100 border border-slate-200/70 px-2 py-0.5 rounded text-slate-700 text-[11px]">{{ $doc->document_number ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($doc->document_date)->translatedFormat('d M Y') }}
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
                            <td class="py-3 px-4 text-slate-400 whitespace-nowrap">
                                {{ $doc->formatted_file_size }}
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

                                    <!-- Delete Button with modern centered modal confirmation -->
                                    <button type="button" 
                                            onclick="openDeleteModal('{{ route('admin.documents.destroy', $doc) }}', '{{ addslashes($doc->title) }}')"
                                            class="w-7 h-7 flex items-center justify-center text-rose-500 hover:text-rose-700 hover:bg-white rounded-lg transition" 
                                            title="Hapus Arsip">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Belum ada dokumen yang diunggah.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
