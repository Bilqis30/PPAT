@extends('layouts.app')

@section('title', 'Upload Dokumen Arsip')
@section('page_title', 'Formulir Upload Arsip')
@section('page_subtitle', 'Entri metadata dokumen resmi dan pengarsipan berkas PDF')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Breadcrumb / Back Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.documents.index') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-900 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Arsip</span>
        </a>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Form Header -->
        <div class="p-4 sm:p-6 md:p-8 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                    <i data-lucide="file-up" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">Metadata Dokumen &amp; Berkas PDF</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Pilih tipe dokumen terlebih dahulu untuk menyesuaikan isian formulir.</p>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" class="p-4 sm:p-6 md:p-8 space-y-5 sm:space-y-6">
            @csrf

            <!-- 1. Klasifikasi Dokumen (Tipe Dokumen & Dynamic Sub-Kategori) -->
            <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6 items-start">
                    <!-- Dropdown Tipe Dokumen -->
                    <div>
                        <label for="type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Tipe Dokumen <span class="text-rose-500">*</span>
                        </label>
                        <select id="type" name="type" required onchange="handleTypeChange(this.value)"
                                class="w-full px-4 py-2.5 bg-white border @error('type') border-rose-300 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                            <option value="" disabled {{ old('type') ? '' : 'selected' }}>-- Pilih Tipe Dokumen --</option>
                            <option value="Surat Masuk" {{ old('type') === 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                            <option value="Surat Keluar" {{ old('type') === 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                            <option value="Berkas" {{ old('type') === 'Berkas' ? 'selected' : '' }}>Berkas (KTP, KK, Sertifikat, dll)</option>
                            <option value="Laporan" {{ old('type') === 'Laporan' ? 'selected' : '' }}>Laporan</option>
                        </select>
                        @error('type')
                            <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dynamic Input Field: Sub-Kategori Laporan (HANYA jika 'Laporan' dipilih) -->
                    <div id="sub-type-container" class="{{ old('type') === 'Laporan' ? 'block' : 'hidden' }} transition-all duration-300">
                        <label for="sub_type" class="block text-xs font-bold text-teal-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i data-lucide="git-branch" class="w-3.5 h-3.5"></i>
                            <span>Klasifikasi Laporan <span class="text-rose-500">*</span></span>
                        </label>
                        <select id="sub_type" name="sub_type"
                                class="w-full px-4 py-2.5 bg-white border @error('sub_type') border-rose-300 @else border-teal-300 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition">
                            <option value="" disabled {{ old('sub_type') ? '' : 'selected' }}>-- Pilih Sub-Kategori Laporan --</option>
                            <option value="Laporan BPN" {{ old('sub_type') === 'Laporan BPN' ? 'selected' : '' }}>Laporan BPN</option>
                            <option value="Laporan Dispenda" {{ old('sub_type') === 'Laporan Dispenda' ? 'selected' : '' }}>Laporan Dispenda</option>
                        </select>
                        <p class="text-[11px] text-teal-600 mt-1">Khusus klasifikasi dokumen bertipe Laporan.</p>
                        @error('sub_type')
                            <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dynamic Input Field: Tanggal Acara (HANYA jika 'Surat Masuk' dipilih) -->
                    <div id="tanggal-acara-container" class="{{ old('type') === 'Surat Masuk' ? 'block' : 'hidden' }} transition-all duration-300">
                        <label for="tanggal_acara" class="block text-xs font-bold text-sky-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-sky-600"></i>
                            <span>Tanggal Acara / Kegiatan (Opsional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="calendar-days" class="w-4 h-4 text-sky-500"></i>
                            </div>
                            <input type="date" id="tanggal_acara" name="tanggal_acara"
                                   value="{{ old('tanggal_acara') }}"
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border @error('tanggal_acara') border-rose-300 @else border-sky-300 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-600 transition">
                        </div>
                        <p class="text-[11px] text-sky-600 mt-1">Khusus Surat Masuk (undangan/kegiatan) untuk pengiriman notifikasi pengingat WhatsApp H-2.</p>
                        @error('tanggal_acara')
                            <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Info Notice saat memilih kategori Berkas -->
                <div id="berkas-notice" class="{{ old('type') === 'Berkas' ? 'flex' : 'hidden' }} items-center gap-2 p-3 bg-amber-50/70 border border-amber-200/80 rounded-xl text-xs text-amber-800 transition">
                    <i data-lucide="info" class="w-4 h-4 text-amber-600 shrink-0"></i>
                    <span>Kategori <strong>Berkas</strong>: Input <strong>Nomor Surat</strong> otomatis disembunyikan karena dokumen berkas (seperti KTP, KK, Akta, Sertifikat tanah) tidak memiliki nomor surat dinas.</span>
                </div>
            </div>

            <!-- 2. Metadata: Nomor Surat (Kondisional) & Tanggal Dokumen -->
            <div id="document-meta-grid" class="grid grid-cols-1 {{ old('type') === 'Berkas' ? '' : 'md:grid-cols-2' }} gap-5 sm:gap-6 transition-all duration-300">
                <!-- Kolom Nomor Surat (Hanya Tampil Jika BUKAN Berkas) -->
                <div id="document-number-container" class="{{ old('type') === 'Berkas' ? 'hidden' : '' }} transition-all duration-300">
                    <label for="document_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nomor Surat / Dokumen <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="hash" class="w-4 h-4"></i>
                        </div>
                        <input type="text" id="document_number" name="document_number" 
                               value="{{ old('document_number') }}"
                               placeholder="Contoh: 005/SM/BPN/IV/2026"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border @error('document_number') border-rose-300 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    </div>
                    @error('document_number')
                        <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kolom Tanggal Dokumen -->
                <div id="document-date-container">
                    <label for="document_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tanggal Dokumen / Pengarsipan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <input type="date" id="document_date" name="document_date" 
                               value="{{ old('document_date', date('Y-m-d')) }}" required
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border @error('document_date') border-rose-300 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    </div>
                    @error('document_date')
                        <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 3. Perihal / Nama Berkas Dokumen -->
            <div>
                <label for="title" id="title-label" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    {{ old('type') === 'Berkas' ? 'Nama Berkas / Perihal Dokumen' : 'Perihal / Judul Dokumen' }} <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="title" name="title" 
                           value="{{ old('title') }}" required
                           placeholder="{{ old('type') === 'Berkas' ? 'Contoh: Berkas KTP & Kartu Keluarga Pemohon, Salinan SHM No. 124' : 'Contoh: Permohonan Validasi Hak Milik Tanah Wilayah B' }}"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border @error('title') border-rose-300 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                </div>
                @error('title')
                    <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- 4. Keterangan / Deskripsi (Opsional) -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Keterangan / Ringkasan Dokumen (Opsional)
                </label>
                <textarea id="description" name="description" rows="3"
                          placeholder="Tambahkan catatan khusus, identitas pemilik berkas, atau ringkasan isi dokumen..."
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-[11px] text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- 5. Upload Berkas PDF (Validasi Ketat & Drag-Drop) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Berkas Dokumen Fisik (PDF) <span class="text-rose-500">*</span>
                </label>
                
                <div id="drop-zone"
                     class="border-2 border-dashed @error('file') border-rose-300 bg-rose-50/20 @else border-slate-300 hover:border-slate-400 bg-slate-50/60 @enderror rounded-2xl p-6 sm:p-8 text-center transition cursor-pointer relative group"
                     onclick="document.getElementById('file-input').click()">
                    
                    <input type="file" id="file-input" name="file" accept="application/pdf" required class="hidden" onchange="handleFileSelected(this)">
                    
                    <div id="upload-placeholder" class="space-y-2">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 text-slate-600 flex items-center justify-center mx-auto shadow-sm group-hover:scale-105 transition">
                            <i data-lucide="file-up" class="w-6 h-6 text-slate-700"></i>
                        </div>
                        <div class="text-xs sm:text-sm font-semibold text-slate-800">
                            Pilih berkas PDF atau seret ke area ini
                        </div>
                        <p class="text-[11px] sm:text-xs text-slate-400">
                            Hanya menerima format <strong>.PDF</strong> dengan ukuran maksimal <strong>20 MB</strong>.
                        </p>
                    </div>

                    <!-- File Selected Info Preview -->
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
                            <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Siap</span>
                            <button type="button" onclick="clearSelectedFile(event)" class="p-1 text-slate-400 hover:text-rose-600 rounded hover:bg-slate-100" title="Ganti File">
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
            <div class="pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('admin.documents.index') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition text-center">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition shadow-sm flex items-center justify-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan &amp; Arsipkan Dokumen</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <!-- Separated Dynamic Document Upload JavaScript -->
    <script src="{{ asset('js/document-upload.js') }}"></script>
@endpush
@endsection
