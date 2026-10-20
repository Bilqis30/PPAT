@extends('layouts.app')

@section('title', 'Edit Layanan - ' . $layanan->title)
@section('page_title', 'Edit Panduan Layanan')
@section('page_subtitle', 'Perbarui detail akta, langkah prosedur, dan syarat kelengkapan berkas')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.layanan.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Layanan</span>
        </a>

        <a href="{{ route('layanan.index') }}#{{ $layanan->slug }}" target="_blank" class="text-xs text-emerald-700 hover:underline flex items-center gap-1 font-medium">
            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            <span>Lihat di Halaman Publik</span>
        </a>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                    <i data-lucide="{{ $layanan->icon ?: 'edit-3' }}" class="w-5 h-5 text-emerald-400"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">Edit: {{ $layanan->title }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui nama akta, badge, langkah prosedur, dan syarat berkas.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.layanan.update', $layanan) }}" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Row 1: Nama Layanan & Slug -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-2">
                    <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nama / Judul Layanan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $layanan->title) }}" 
                           required
                           class="w-full px-4 py-3 bg-white border @error('title') border-rose-300 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    @error('title')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Slug / ID Anchor
                    </label>
                    <input type="text" 
                           id="slug" 
                           name="slug" 
                           value="{{ old('slug', $layanan->slug) }}" 
                           placeholder="contoh: ajb"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    <p class="text-[11px] text-slate-400 mt-1">Digunakan untuk jump-link (#ajb).</p>
                </div>
            </div>

            <!-- Row 2: Badge, Warna Badge, Ikon & Urutan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                <div>
                    <label for="badge" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Badge Kategori
                    </label>
                    <input type="text" 
                           id="badge" 
                           name="badge" 
                           value="{{ old('badge', $layanan->badge) }}" 
                           placeholder="Contoh: Paling Sering Digunakan"
                           class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                </div>

                <div>
                    <label for="badge_color" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Warna Badge <span class="text-rose-500">*</span>
                    </label>
                    <select id="badge_color" 
                            name="badge_color" 
                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                        @php
                            $colors = ['emerald' => 'Hijau Emerald', 'sky' => 'Biru Langit', 'indigo' => 'Indigo / Ungu Muda', 'amber' => 'Kuning Amber', 'purple' => 'Ungu Purple', 'teal' => 'Teal / Biru Kehijauan', 'rose' => 'Merah Rose'];
                            $selectedColor = old('badge_color', $layanan->badge_color ?? 'emerald');
                        @endphp
                        @foreach($colors as $val => $label)
                            <option value="{{ $val }}" {{ $selectedColor == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="icon" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Ikon Lucide <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="icon" 
                           name="icon" 
                           value="{{ old('icon', $layanan->icon) }}" 
                           required
                           list="icon-suggestions"
                           placeholder="contoh: handshake"
                           class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    <datalist id="icon-suggestions">
                        <option value="handshake">
                        <option value="gift">
                        <option value="users">
                        <option value="shield-check">
                        <option value="split">
                        <option value="search-check">
                        <option value="file-text">
                        <option value="home">
                        <option value="scale">
                    </datalist>
                </div>

                <div>
                    <label for="order_num" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nomor Urutan
                    </label>
                    <input type="number" 
                           id="order_num" 
                           name="order_num" 
                           value="{{ old('order_num', $layanan->order_num) }}" 
                           class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                </div>
            </div>

            <!-- Row 3: Estimasi Waktu & Status Aktif -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-center">
                <div class="md:col-span-2">
                    <label for="estimated_time" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Estimasi Durasi / Waktu Pengurusan
                    </label>
                    <input type="text" 
                           id="estimated_time" 
                           name="estimated_time" 
                           value="{{ old('estimated_time', $layanan->estimated_time) }}" 
                           placeholder="Contoh: 1 - 2 minggu (tergantung validasi pajak)"
                           class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                </div>

                <div class="pt-2 sm:pt-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $layanan->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded text-slate-900 focus:ring-slate-800 border-slate-300">
                        <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Tampilkan di Publik (Aktif)</span>
                    </label>
                </div>
            </div>

            <!-- Deskripsi Layanan -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Deskripsi Ringkas Layanan <span class="text-rose-500">*</span>
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3" 
                          required
                          class="w-full px-4 py-3 bg-white border @error('description') border-rose-300 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">{{ old('description', $layanan->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Section 1: Langkah-langkah Prosedur -->
            <div class="p-5 bg-slate-50/80 rounded-2xl border border-slate-200 space-y-2">
                <div class="flex items-center justify-between">
                    <label for="steps" class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                        Langkah-Langkah &amp; Prosedur Pengurusan
                    </label>
                    <span class="text-[11px] text-slate-500 font-medium">Tulis 1 langkah per baris (Enter)</span>
                </div>
                <p class="text-[11px] text-slate-500">Setiap baris teks yang Anda tulis akan otomatis menjadi 1 langkah bernomor di halaman web publik.</p>
                <textarea id="steps" 
                          name="steps" 
                          rows="6" 
                          placeholder="Contoh:&#10;Pemeriksaan & Validasi Sertifikat di BPN...&#10;Pembayaran Pajak PPh dan BPHTB...&#10;Penandatanganan Akta di hadapan PPAT..."
                          class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition leading-relaxed">{{ old('steps', $layanan->arrayToLines('steps')) }}</textarea>
            </div>

            <!-- Section 2: Syarat Penjual / Pihak Pertama -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="p-5 bg-slate-50/80 rounded-2xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="requirements_penjual" class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Syarat Pihak Pertama / Penjual / Pemohon
                        </label>
                    </div>
                    <p class="text-[11px] text-slate-500">Tulis 1 dokumen/syarat per baris (Enter).</p>
                    <textarea id="requirements_penjual" 
                              name="requirements_penjual" 
                              rows="8" 
                              placeholder="Contoh:&#10;Sertifikat Asli Tanah&#10;KTP Penjual & Pasangan&#10;Kartu Keluarga (KK)&#10;SPPT PBB Terakhir + Bukti Lunas..."
                              class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition leading-relaxed">{{ old('requirements_penjual', $layanan->arrayToLines('requirements_penjual')) }}</textarea>
                </div>

                <!-- Section 3: Syarat Pembeli / Pihak Kedua -->
                <div class="p-5 bg-slate-50/80 rounded-2xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="requirements_pembeli" class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Syarat Pihak Kedua / Pembeli / Penerima
                        </label>
                    </div>
                    <p class="text-[11px] text-slate-500">Tulis 1 dokumen/syarat per baris (Enter).</p>
                    <textarea id="requirements_pembeli" 
                              name="requirements_pembeli" 
                              rows="8" 
                              placeholder="Contoh:&#10;KTP Pembeli & Pasangan&#10;Kartu Keluarga (KK) Pembeli&#10;NPWP Pembeli&#10;Bukti Setor & Validasi BPHTB..."
                              class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition leading-relaxed">{{ old('requirements_pembeli', $layanan->arrayToLines('requirements_pembeli')) }}</textarea>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('layanan.index') }}#{{ $layanan->slug }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 transition">
                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                    <span>Preview di Website</span>
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.layanan.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold transition">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition shadow-xs">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-400"></i>
                        <span>Simpan Perubahan Layanan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
