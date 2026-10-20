@extends('layouts.app')

@section('title', 'Detail Arsip - ' . $document->document_number)
@section('page_title', 'Detail Dokumen Arsip')
@section('page_subtitle', 'Informasi lengkap dan metadata berkas kearsipan')

@section('topbar_actions')
    <a href="{{ Auth::user()->isAdmin() ? route('admin.documents.index') : route('pimpinan.monitoring') }}" 
       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
        <span>Kembali ke Daftar</span>
    </a>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-5 sm:space-y-6">
    <!-- Metadata Overview Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:p-8">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 pb-5 sm:pb-6 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="font-mono text-xs font-bold bg-slate-100 text-slate-800 px-2.5 py-1 rounded-lg">
                        {{ $document->document_number ?? 'Tanpa Nomor Surat (Berkas)' }}
                    </span>
                    @if($document->type === 'Surat Masuk')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">
                            Surat Masuk
                        </span>
                    @elseif($document->type === 'Surat Keluar')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            Surat Keluar
                        </span>
                    @elseif($document->type === 'Berkas')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            Berkas
                        </span>
                    @elseif($document->type === 'Laporan')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-teal-50 text-teal-700 border border-teal-200">
                            {{ $document->sub_type ?? 'Laporan' }}
                        </span>
                    @endif
                </div>
                <h1 class="text-lg sm:text-2xl font-bold text-slate-900 tracking-tight">{{ $document->title }}</h1>
            </div>

            <div class="flex items-center gap-2 self-start">
                <a href="{{ route('documents.download', $document) }}" 
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition shadow-sm">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Unduh PDF</span>
                </a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.documents.edit', $document) }}"
                       class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 border border-slate-200 transition"
                       title="Edit Dokumen">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </a>
                    <button type="button" 
                            onclick="openDeleteModal('{{ route('admin.documents.destroy', $document) }}', '{{ addslashes($document->title) }}')"
                            class="p-2 rounded-xl text-rose-600 hover:bg-rose-50 border border-rose-200 transition" 
                            title="Hapus Dokumen">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                @endif
            </div>
        </div>

        <!-- Metadata Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 py-5 sm:py-6 border-b border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 block mb-1">Tanggal Dokumen</span>
                <span class="font-semibold text-slate-800 text-xs sm:text-sm">
                    {{ \Carbon\Carbon::parse($document->document_date)->translatedFormat('d F Y') }}
                </span>
            </div>
            @if($document->type === 'Surat Masuk' && $document->tanggal_acara)
                <div>
                    <span class="text-sky-600 font-semibold flex items-center gap-1 mb-1">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        <span>Tanggal Acara</span>
                    </span>
                    <span class="font-bold text-sky-900 text-xs sm:text-sm bg-sky-50 px-2.5 py-0.5 rounded-lg border border-sky-200 inline-block">
                        {{ \Carbon\Carbon::parse($document->tanggal_acara)->translatedFormat('d F Y') }}
                    </span>
                </div>
            @endif
            <div>
                <span class="text-slate-400 block mb-1">Pengunggah / Arsiparis</span>
                <span class="font-semibold text-slate-800 text-xs sm:text-sm">
                    {{ $document->user->name ?? 'Admin Sistem' }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 block mb-1">Nama Berkas Fisik</span>
                <span class="font-semibold text-slate-800 text-xs sm:text-sm truncate block" title="{{ $document->file_name }}">
                    {{ $document->file_name }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 block mb-1">Ukuran Berkas</span>
                <span class="font-semibold text-slate-800 text-xs sm:text-sm">
                    {{ $document->formatted_file_size }}
                </span>
            </div>
        </div>

        @if($document->description)
            <div class="pt-5 sm:pt-6">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Ringkasan &amp; Keterangan</span>
                <p class="text-xs sm:text-sm text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-100 leading-relaxed">
                    {{ $document->description }}
                </p>
            </div>
        @endif
    </div>

    <!-- Embedded PDF Viewer Box -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-3.5 sm:p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-red-500"></i>
                <span class="text-xs font-bold text-slate-800">Pratinjau PDF Terintegrasi</span>
            </div>
            <a href="{{ route('documents.preview', $document) }}" target="_blank" 
               class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1">
                <span>Tab Baru</span>
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            </a>
        </div>
        <div class="w-full h-[400px] sm:h-[650px] bg-slate-100">
            <iframe src="{{ route('documents.preview', $document) }}" class="w-full h-full border-0"></iframe>
        </div>
    </div>
</div>
@endsection
