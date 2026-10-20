@extends('layouts.app')

@section('title', 'Edit Dokumen - ' . $document->document_number)
@section('page_title', 'Edit Dokumen Arsip')
@section('page_subtitle', 'Perbarui metadata atau ganti berkas PDF dokumen yang telah diarsipkan')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Main Card Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Form Header -->
        <div class="p-5 sm:p-8 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                        <i data-lucide="file-pen" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Edit Metadata & Berkas PDF</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Kosongkan field berkas PDF jika tidak ingin mengganti file yang ada.</p>
                    </div>
                </div>
                <!-- Badge Nomor Surat -->
                <span class="hidden sm:inline-flex font-mono text-[11px] font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg border border-slate-200 shrink-0">
                    {{ $document->document_number ?? 'Berkas' }}
                </span>
            </div>
        </div>

        <!-- Form Body -->
        <form method="POST" action="{{ route('admin.documents.update', $document) }}" enctype="multipart/form-data" class="p-5 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- 1. Klasifikasi Dokumen -->
            <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6 items-start">
                    <!-- Dropdown Tipe Dokumen -->
                    <div>
                        <label for="type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Tipe Dokumen <span class="text-rose-500">*</span>
                        </label>
                        <select id="type" name="type" required onchange="handleTypeChange(this.value)"
                                class="w-full px-4 py-2.5 bg-white border @error('type') border-rose-300 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                            <option value="Surat Masuk" {{ old('type', $document->type) === 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                            <option value="Surat Keluar" {{ old('type', $document->type) === 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                            <option value="Berkas" {{ old('type', $document->type) === 'Berkas' ? 'selected' : '' }}>Berkas (KTP, KK, Sertifikat, dll)</option>
                            <option value="Laporan" {{ old('type', $document->type) === 'Laporan' ? 'selected' : '' }}>Laporan</option>
                        </select>
                        @error('type')
                            <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sub-Kategori Laporan -->
                    <div id="sub-type-container" class="{{ old('type', $document->type) === 'Laporan' ? 'block' : 'hidden' }} transition-all duration-300">
                        <label for="sub_type" class="block text-xs font-bold text-teal-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i data-lucide="git-branch" class="w-3.5 h-3.5"></i>
                            <span>Klasifikasi Laporan <span class="text-rose-500">*</span></span>
                        </label>
                        <select id="sub_type" name="sub_type"
                                class="w-full px-4 py-2.5 bg-white border @error('sub_type') border-rose-300 @else border-teal-300 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition">
                            <option value="" disabled>-- Pilih Sub-Kategori --</option>
                            <option value="Laporan BPN" {{ old('sub_type', $document->sub_type) === 'Laporan BPN' ? 'selected' : '' }}>Laporan BPN</option>
                            <option value="Laporan Dispenda" {{ old('sub_type', $document->sub_type) === 'Laporan Dispenda' ? 'selected' : '' }}>Laporan Dispenda</option>
                        </select>
                        @error('sub_type')
                            <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Acara (Surat Masuk) -->
                    <div id="tanggal-acara-container" class="{{ old('type', $document->type) === 'Surat Masuk' ? 'block' : 'hidden' }} transition-all duration-300">
                        <label for="tanggal_acara" class="block text-xs font-bold text-sky-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-sky-600"></i>
                            <span>Tanggal Acara / Kegiatan (Opsional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="calendar-days" class="w-4 h-4 text-sky-500"></i>
                            </div>
                            <input type="date" id="tanggal_acara" name="tanggal_acara"
                                   value="{{ old('tanggal_acara', $document->tanggal_acara?->format('Y-m-d')) }}"
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border @error('tanggal_acara') border-rose-300 @else border-sky-300 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-600 transition">
                        </div>
                        @error('tanggal_acara')
                            <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Info Notice Berkas -->
                <div id="berkas-notice" class="{{ old('type', $document->type) === 'Berkas' ? 'flex' : 'hidden' }} items-center gap-2 p-3 bg-amber-50/70 border border-amber-200/80 rounded-xl text-xs text-amber-800 transition">
                    <i data-lucide="info" class="w-4 h-4 text-amber-600 shrink-0"></i>
                    <span>Kategori <strong>Berkas</strong>: Nomor Surat otomatis disembunyikan karena tidak diperlukan.</span>
                </div>
            </div>

            <!-- 2. Nomor Surat & Tanggal Dokumen -->
            <div id="document-meta-grid" class="grid grid-cols-1 {{ old('type', $document->type) === 'Berkas' ? '' : 'md:grid-cols-2' }} gap-5 sm:gap-6 transition-all duration-300">
                <!-- Nomor Surat -->
                <div id="document-number-container" class="{{ old('type', $document->type) === 'Berkas' ? 'hidden' : '' }} transition-all duration-300">
                    <label for="document_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nomor Surat / Dokumen <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="hash" class="w-4 h-4"></i>
                        </div>
                        <input type="text" id="document_number" name="document_number"
                               value="{{ old('document_number', $document->document_number) }}"
                               placeholder="Contoh: 005/SM/BPN/IV/2026"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border @error('document_number') border-rose-300 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    </div>
                    @error('document_number')
                        <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Dokumen -->
                <div>
                    <label for="document_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tanggal Dokumen / Pengarsipan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <input type="date" id="document_date" name="document_date"
                               value="{{ old('document_date', $document->document_date instanceof \Carbon\Carbon ? $document->document_date->format('Y-m-d') : $document->document_date) }}" required
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border @error('document_date') border-rose-300 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    </div>
                    @error('document_date')
                        <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 3. Perihal / Judul -->
            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Perihal / Judul Dokumen <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="title" name="title"
                           value="{{ old('title', $document->title) }}" required
                           placeholder="Contoh: Permohonan Validasi Hak Milik Tanah Wilayah B"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border @error('title') border-rose-300 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                </div>
                @error('title')
                    <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- 4. Keterangan / Deskripsi -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Keterangan / Ringkasan Dokumen (Opsional)
                </label>
                <textarea id="description" name="description" rows="3"
                          placeholder="Tambahkan catatan khusus, identitas pemilik berkas, atau ringkasan isi dokumen..."
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">{{ old('description', $document->description) }}</textarea>
                @error('description')
                    <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- 5. Ganti Berkas PDF (Opsional) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Ganti Berkas PDF <span class="text-slate-400 font-normal normal-case">(Opsional — kosongkan jika tidak ingin mengganti)</span>
                </label>

                <!-- Info file saat ini -->
                <div class="flex items-center gap-2.5 p-3 mb-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600">
                    <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-800 truncate">{{ $document->file_name }}</p>
                        <p class="text-[11px] text-slate-400">{{ $document->formatted_file_size }} — Berkas aktif saat ini</p>
                    </div>
                </div>

                <div id="drop-zone"
                     class="border-2 border-dashed @error('file') border-rose-300 bg-rose-50/20 @else border-slate-300 hover:border-slate-400 bg-slate-50/60 @enderror rounded-2xl p-5 sm:p-7 text-center transition cursor-pointer relative group"
                     onclick="document.getElementById('file-input').click()">

                    <input type="file" id="file-input" name="file" accept="application/pdf" class="hidden" onchange="handleFileSelected(this)">

                    <div id="upload-placeholder" class="space-y-2">
                        <div class="w-11 h-11 rounded-2xl bg-white border border-slate-200 text-slate-600 flex items-center justify-center mx-auto shadow-sm group-hover:scale-105 transition">
                            <i data-lucide="file-up" class="w-5 h-5 text-slate-700"></i>
                        </div>
                        <div class="text-xs sm:text-sm font-semibold text-slate-700">Pilih PDF baru atau seret ke sini</div>
                        <p class="text-[11px] text-slate-400">Format <strong>.PDF</strong>, maks <strong>20 MB</strong></p>
                    </div>

                    <div id="upload-selected-info" class="hidden items-center justify-between gap-3 p-3 bg-white rounded-xl border border-slate-200 text-left max-w-md mx-auto shadow-sm">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-9 h-9 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0">
                                <p id="selected-file-name" class="text-xs font-semibold text-slate-900 truncate">nama_file.pdf</p>
                                <p id="selected-file-size" class="text-[11px] text-slate-400">0 KB</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Siap Ganti</span>
                            <button type="button" onclick="clearSelectedFile(event)" class="p-1 text-slate-400 hover:text-rose-600 rounded hover:bg-slate-100" title="Batal Ganti">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                @error('file')
                    <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button Bar -->
            <div class="pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('documents.show', $document) }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition text-center">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition shadow-sm flex items-center justify-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/document-upload.js') }}"></script>
@endpush
@endsection
