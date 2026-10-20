@extends('layouts.app')

@section('title', 'Dashboard Eksekutif')
@section('page_title', 'Executive Summary Monitoring Arsip')
@section('page_subtitle', 'Pemantauan volume, tren, dan sebaran dokumen kearsipan secara komprehensif')


@section('content')
<div class="space-y-6 sm:space-y-8">
    <!-- Executive Banner -->
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-8 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-5 sm:gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-700 mb-3">
                <i data-lucide="eye" class="w-3.5 h-3.5 text-emerald-600"></i>
                <span>Mode Pemantauan Eksekutif (Read-Only)</span>
            </div>
            <h2 class="text-lg sm:text-2xl font-bold text-slate-900 tracking-tight">Ringkasan Eksekutif Kearsipan</h2>
            <p class="text-slate-500 text-xs sm:text-sm mt-1 max-w-2xl">
                Pantau seluruh aktivitas kearsipan dinas dari satu pintu secara transparan dan akuntabel.
            </p>
        </div>
        
    </div>

    <!-- Pusat Pemberitahuan & Notifikasi Pimpinan -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-5 sm:p-6 text-white shadow-sm border border-slate-700/50">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-400/20 border border-amber-400/30 text-amber-400 flex items-center justify-center shrink-0 mt-0.5">
                    <i data-lucide="bell-ring" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="font-bold text-sm sm:text-base text-white">Pemberitahuan &amp; Notifikasi Pimpinan</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            {{ $topbarNotificationCount ?? 0 }} Berkas Terkini
                        </span>
                    </div>
                    <p class="text-xs text-slate-300 mt-1 leading-relaxed max-w-2xl">
                        Sistem memantau seluruh dokumen warkah, surat masuk, dan laporan berkala yang baru saja diunggah oleh staf pengelola arsip.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <button type="button" onclick="toggleNotificationDropdown()" 
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold border border-white/20 transition cursor-pointer">
                    <i data-lucide="bell" class="w-4 h-4 text-amber-400"></i>
                    <span>Rincian Notifikasi</span>
                </button>
                <a href="{{ route('pimpinan.monitoring') }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white text-slate-900 hover:bg-slate-100 text-xs font-semibold transition shadow-sm">
                    <i data-lucide="scan-search" class="w-4 h-4"></i>
                    <span>Buka Monitoring</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 6 Metric Cards (Responsive Grid: 2 cols on mobile, 3 on md, 6 on xl) -->
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4">
        <!-- 1. Total Arsip -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-slate-400">Total Arsip</span>
                <i data-lucide="archive" class="w-4 h-4 text-slate-700"></i>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ number_format($stats['total']) }}</div>
            <span class="text-[10px] text-slate-400">Semua dokumen</span>
        </div>

        <!-- 2. Surat Masuk -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-sky-600">Surat Masuk</span>
                <i data-lucide="inbox" class="w-4 h-4 text-sky-600"></i>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ number_format($stats['surat_masuk']) }}</div>
            <span class="text-[10px] text-slate-400">Eksternal</span>
        </div>

        <!-- 3. Surat Keluar -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-indigo-600">Surat Keluar</span>
                <i data-lucide="send" class="w-4 h-4 text-indigo-600"></i>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ number_format($stats['surat_keluar']) }}</div>
            <span class="text-[10px] text-slate-400">Dinas keluar</span>
        </div>

        <!-- 4. Berkas -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-amber-600">Berkas</span>
                <i data-lucide="folder" class="w-4 h-4 text-amber-600"></i>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ number_format($stats['berkas']) }}</div>
            <span class="text-[10px] text-slate-400">Dokumentasi</span>
        </div>

        <!-- 5. Laporan BPN -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-teal-600">Laporan BPN</span>
                <i data-lucide="landmark" class="w-4 h-4 text-teal-600"></i>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ number_format($stats['laporan_bpn']) }}</div>
            <span class="text-[10px] text-slate-400">Pertanahan</span>
        </div>

        <!-- 6. Laporan Dispenda -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-violet-600">Dispenda</span>
                <i data-lucide="receipt" class="w-4 h-4 text-violet-600"></i>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ number_format($stats['laporan_dispenda']) }}</div>
            <span class="text-[10px] text-slate-400">PAD &amp; Pajak</span>
        </div>
    </div>

    <!-- Charts Section (Executive Analytics) -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 sm:gap-6">
        <!-- Chart 1: Donut Chart Sebaran Dokumen -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm flex flex-col">
            <div class="mb-4">
                <h3 class="font-bold text-slate-900 text-sm">Proporsi Kategori Dokumen</h3>
                <p class="text-xs text-slate-400 mt-0.5">Persentase distribusi volume per kategori</p>
            </div>
            <div class="flex-1 flex items-center justify-center min-h-[240px] sm:min-h-[260px] relative">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Bar Chart Tren Bulanan -->
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm flex flex-col">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Tren Volume Arsip Masuk</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Jumlah dokumen yang terarsipkan dalam 6 bulan terakhir</p>
                </div>
                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">Semester Ini</span>
            </div>
            <div class="flex-1 flex items-center justify-center min-h-[240px] sm:min-h-[260px]">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Documents Table for Pimpinan -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">Arsip Terbaru Yang Perlu Diperhatikan</h3>
                <p class="text-xs text-slate-400 mt-0.5">Dapat langsung dipratinjau secara inline tanpa keluar dari aplikasi</p>
            </div>
            <a href="{{ route('pimpinan.monitoring') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1">
                <span class="hidden sm:inline">Buka Menu Monitoring</span>
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
                        <th class="py-3 px-4 whitespace-nowrap">Kategori</th>
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
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                Belum ada dokumen kearsipan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Decoupled Chart Datasets for external pimpinan-dashboard.js -->
<div id="chart-data" 
     data-category-labels='@json($categoryBreakdown["labels"])'
     data-category-values='@json($categoryBreakdown["data"])'
     data-monthly-labels='@json($monthlyLabels)'
     data-monthly-values='@json($monthlyData)'
     class="hidden"></div>

@push('scripts')
    <!-- Separated Dashboard Charts JavaScript -->
    <script src="{{ asset('js/pimpinan-dashboard.js') }}"></script>
@endpush
@endsection
