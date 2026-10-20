@extends('layouts.app')

@section('title', 'Edit Materi - ' . $materi->title)
@section('page_title', 'Edit Materi & Artikel')
@section('page_subtitle', 'Perbarui informasi, foto sampul, atau konten materi publik')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.materi.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Materi</span>
        </a>

        <a href="{{ route('materi.show', $materi->slug) }}" target="_blank" class="text-xs text-emerald-700 hover:underline flex items-center gap-1 font-medium">
            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            <span>Buka Halaman Materi Publik</span>
        </a>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                    <i data-lucide="edit-3" class="w-5 h-5 text-emerald-400"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">Edit Materi: {{ $materi->title }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui data atau ganti foto sampul materi.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.materi.update', $materi) }}" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Judul -->
            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Judul Materi / Artikel <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $materi->title) }}" 
                       required
                       class="w-full px-4 py-3 bg-white border @error('title') border-rose-300 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                @error('title')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid: Kategori, Tanggal Terbit & Penulis -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label for="category" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select id="category" 
                            name="category" 
                            required
                            class="w-full px-4 py-2.5 bg-white border @error('category') border-rose-300 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                        <option value="Sosialisasi" {{ old('category', $materi->category) == 'Sosialisasi' ? 'selected' : '' }}>Sosialisasi</option>
                        <option value="Artikel" {{ old('category', $materi->category) == 'Artikel' ? 'selected' : '' }}>Artikel</option>
                        @if(!empty($materi->category) && !in_array($materi->category, ['Sosialisasi', 'Artikel']))
                            <option value="{{ $materi->category }}" selected>{{ $materi->category }}</option>
                        @endif
                    </select>
                    @error('category')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="published_at" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tanggal Terbit <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" 
                           id="published_at" 
                           name="published_at" 
                           value="{{ old('published_at', $materi->published_at ? $materi->published_at->format('Y-m-d') : date('Y-m-d')) }}" 
                           required
                           class="w-full px-4 py-2.5 bg-white border @error('published_at') border-rose-300 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    @error('published_at')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="author_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nama Penulis / Instansi
                    </label>
                    <input type="text" 
                           id="author_name" 
                           name="author_name" 
                           value="{{ old('author_name', $materi->author_name) }}" 
                           class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                </div>
            </div>

            <!-- Fitur Ganti Background / Foto Sampul Materi -->
            <div class="p-6 bg-slate-50/80 rounded-2xl border border-slate-200 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-200/80 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center">
                            <i data-lucide="image" class="w-4 h-4 text-emerald-700"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Fitur Ganti Background / Foto Sampul Materi</h4>
                            <p class="text-[11px] text-slate-500">Unggah foto baru dari komputer/HP atau pilih langsung dari galeri foto siap pakai.</p>
                        </div>
                    </div>
                </div>

                <!-- Live Preview & Upload Zone -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
                    <!-- Preview Box -->
                    <div class="md:col-span-5 space-y-2">
                        <span class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Preview Background / Sampul</span>
                        <div class="relative aspect-video rounded-xl overflow-hidden bg-slate-200 border-2 border-slate-300 shadow-sm group">
                            <img id="image-preview" src="{{ $materi->cover_image_url }}" alt="Preview Background" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-3 pointer-events-none">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#760604] text-white">
                                    {{ $materi->category ?? 'Artikel' }}
                                </span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 text-center">Tampilan kartu background di website publik</p>
                    </div>

                    <!-- Upload Input & URL -->
                    <div class="md:col-span-7 space-y-4">
                        <!-- Option A: Upload File Baru -->
                        <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-2xs space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                1. Upload File Gambar Dari Komputer / HP
                            </label>
                            <input type="file" 
                                   id="cover_image" 
                                   name="cover_image" 
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   onchange="previewCoverImage(this)"
                                   class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                            <p class="text-[11px] text-slate-400">Format: JPG, PNG, WEBP (Maksimal 5MB).</p>
                        </div>

                        <!-- Option B: Input URL Gambar -->
                        <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-2xs space-y-2">
                            <label for="custom_cover_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                2. Atau Gunakan Link / URL Gambar (Opsional)
                            </label>
                            <input type="url" 
                                   id="custom_cover_url" 
                                   name="custom_cover_url" 
                                   placeholder="https://example.com/gambar.jpg"
                                   oninput="previewFromUrl(this.value)"
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800">
                        </div>
                    </div>
                </div>

                <!-- Hidden Input for Preset -->
                <input type="hidden" id="preset_cover" name="preset_cover" value="">

                <!-- Option C: Galeri Pilihan Siap Pakai -->
                <div class="pt-3 border-t border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            3. Atau Pilih Cepat Dari Galeri Foto Siap Pakai (1-Klik)
                        </label>
                        <span class="text-[11px] text-slate-400">Klik salah satu foto di bawah:</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <!-- Preset 1: Kantor PPAT -->
                        <button type="button" 
                                onclick="selectPreset('images/hero-kantor-ppat.jpg', this)"
                                class="preset-btn text-left p-1.5 rounded-xl border-2 border-slate-200 hover:border-emerald-500 bg-white transition focus:outline-none group">
                            <div class="aspect-video rounded-lg overflow-hidden bg-slate-100 mb-1.5 relative">
                                <img src="{{ asset('images/hero-kantor-ppat.jpg') }}" alt="Kantor PPAT" class="w-full h-full object-cover">
                                <span class="preset-check hidden absolute top-1 right-1 w-4 h-4 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">✓</span>
                            </div>
                            <span class="block text-[11px] font-bold text-slate-800 truncate">Kantor PPAT Resmi</span>
                            <span class="block text-[10px] text-slate-400">Gedung &amp; Plang</span>
                        </button>

                        <!-- Preset 2: Danau & Hutan -->
                        <button type="button" 
                                onclick="selectPreset('images/materi/batanghari_sembilan.jpg', this)"
                                class="preset-btn text-left p-1.5 rounded-xl border-2 border-slate-200 hover:border-emerald-500 bg-white transition focus:outline-none group">
                            <div class="aspect-video rounded-lg overflow-hidden bg-slate-100 mb-1.5 relative">
                                <img src="{{ asset('images/materi/batanghari_sembilan.jpg') }}" alt="Batanghari Sembilan" class="w-full h-full object-cover">
                                <span class="preset-check hidden absolute top-1 right-1 w-4 h-4 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">✓</span>
                            </div>
                            <span class="block text-[11px] font-bold text-slate-800 truncate">Danau &amp; Pegunungan</span>
                            <span class="block text-[10px] text-slate-400">Nuansa Alam Asri</span>
                        </button>

                        <!-- Preset 3: Agroforestri Tambang -->
                        <button type="button" 
                                onclick="selectPreset('images/materi/agroforestri_tambang.jpg', this)"
                                class="preset-btn text-left p-1.5 rounded-xl border-2 border-slate-200 hover:border-emerald-500 bg-white transition focus:outline-none group">
                            <div class="aspect-video rounded-lg overflow-hidden bg-slate-100 mb-1.5 relative">
                                <img src="{{ asset('images/materi/agroforestri_tambang.jpg') }}" alt="Agroforestri" class="w-full h-full object-cover">
                                <span class="preset-check hidden absolute top-1 right-1 w-4 h-4 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">✓</span>
                            </div>
                            <span class="block text-[11px] font-bold text-slate-800 truncate">Lahan &amp; Lingkungan</span>
                            <span class="block text-[10px] text-slate-400">Koleksi Alam</span>
                        </button>

                        <!-- Preset 4: Hutan Tropis -->
                        <button type="button" 
                                onclick="selectPreset('images/hero-forest-bg.jpg', this)"
                                class="preset-btn text-left p-1.5 rounded-xl border-2 border-slate-200 hover:border-emerald-500 bg-white transition focus:outline-none group">
                            <div class="aspect-video rounded-lg overflow-hidden bg-slate-100 mb-1.5 relative">
                                <img src="{{ asset('images/hero-forest-bg.jpg') }}" alt="Hutan Tropis" class="w-full h-full object-cover">
                                <span class="preset-check hidden absolute top-1 right-1 w-4 h-4 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">✓</span>
                            </div>
                            <span class="block text-[11px] font-bold text-slate-800 truncate">Hutan Tropis</span>
                            <span class="block text-[10px] text-slate-400">Hijau &amp; Pepohonan</span>
                        </button>
                    </div>
                </div>

                @error('cover_image')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Excerpt -->
            <div>
                <label for="excerpt" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Ringkasan Singkat
                </label>
                <textarea id="excerpt" 
                          name="excerpt" 
                          rows="2" 
                          class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">{{ old('excerpt', $materi->excerpt) }}</textarea>
            </div>

            <!-- Content with Formatting Toolbar -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="content" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Isi Materi / Artikel Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <span id="char-count" class="text-[11px] text-slate-400">0 karakter</span>
                </div>

                <!-- Text Formatting Helper Toolbar -->
                <div class="flex flex-wrap items-center gap-1.5 p-2 bg-slate-100/90 border border-slate-200 rounded-t-xl text-xs text-slate-700">
                    <button type="button" onclick="insertFormat('**', '**')" title="Tebal (Bold)" class="px-2.5 py-1 bg-white hover:bg-slate-200 border border-slate-300 rounded font-bold shadow-2xs">B</button>
                    <button type="button" onclick="insertFormat('*', '*')" title="Miring (Italic)" class="px-2.5 py-1 bg-white hover:bg-slate-200 border border-slate-300 rounded italic shadow-2xs">I</button>
                    <span class="text-slate-300">|</span>
                    <button type="button" onclick="insertFormat('## ', '')" title="Sub-Judul" class="px-2.5 py-1 bg-white hover:bg-slate-200 border border-slate-300 rounded font-semibold text-[11px] shadow-2xs">Judul Sub-Bab</button>
                    <button type="button" onclick="insertFormat('- ', '')" title="Daftar Poin" class="px-2.5 py-1 bg-white hover:bg-slate-200 border border-slate-300 rounded shadow-2xs">&bull; Poin</button>
                    <button type="button" onclick="insertFormat('1. ', '')" title="Daftar Nomor" class="px-2.5 py-1 bg-white hover:bg-slate-200 border border-slate-300 rounded shadow-2xs">1. Nomor</button>
                    <button type="button" onclick="insertFormat('> ', '')" title="Kutipan / Catatan Penting" class="px-2.5 py-1 bg-white hover:bg-slate-200 border border-slate-300 rounded shadow-2xs">&ldquo; Kutipan</button>
                    <span class="text-slate-300">|</span>
                    <button type="button" onclick="insertFormat('\n\n', '')" title="Paragraf Baru" class="px-2.5 py-1 bg-white hover:bg-slate-200 border border-slate-300 rounded text-[11px] shadow-2xs">Enter Baru</button>
                </div>

                <textarea id="content" 
                          name="content" 
                          rows="12" 
                          required
                          oninput="updateCharCount()"
                          class="w-full px-4 py-3 bg-white border @error('content') border-rose-300 @else border-slate-200 @enderror rounded-b-xl rounded-t-none text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition leading-relaxed font-sans">{{ old('content', $materi->content) }}</textarea>
                @error('content')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('materi.show', $materi->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 transition">
                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                    <span>Lihat Halaman Publik</span>
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.materi.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold transition">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition shadow-sm">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-400"></i>
                        <span>Simpan Perubahan Tulisan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>

<script>
    function previewCoverImage(input) {
        const preview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
            // Clear preset selection when file is chosen
            document.getElementById('preset_cover').value = '';
            document.querySelectorAll('.preset-btn').forEach(btn => {
                btn.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/30');
                btn.querySelector('.preset-check')?.classList.add('hidden');
            });
        }
    }

    function selectPreset(path, buttonElement) {
        // Set hidden preset input
        document.getElementById('preset_cover').value = path;
        // Clear file input
        const fileInput = document.getElementById('cover_image');
        if (fileInput) fileInput.value = '';
        // Clear url input
        const urlInput = document.getElementById('custom_cover_url');
        if (urlInput) urlInput.value = '';

        // Update preview image
        const preview = document.getElementById('image-preview');
        const assetBase = "{{ asset('') }}";
        preview.src = assetBase + path;

        // Update active highlight style
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/30');
            btn.querySelector('.preset-check')?.classList.add('hidden');
        });
        if (buttonElement) {
            buttonElement.classList.add('border-emerald-500', 'ring-2', 'ring-emerald-500/30');
            buttonElement.querySelector('.preset-check')?.classList.remove('hidden');
        }
    }

    function previewFromUrl(url) {
        if (!url || !url.startsWith('http')) return;
        const preview = document.getElementById('image-preview');
        preview.src = url;
        document.getElementById('preset_cover').value = '';
        const fileInput = document.getElementById('cover_image');
        if (fileInput) fileInput.value = '';
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/30');
            btn.querySelector('.preset-check')?.classList.add('hidden');
        });
    }

    function insertFormat(startTag, endTag) {
        const textarea = document.getElementById('content');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const selected = text.substring(start, end);
        const replacement = startTag + selected + endTag;
        textarea.value = text.substring(0, start) + replacement + text.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + startTag.length, start + startTag.length + selected.length);
        updateCharCount();
    }

    function updateCharCount() {
        const textarea = document.getElementById('content');
        const count = textarea.value.length;
        const words = textarea.value.trim() ? textarea.value.trim().split(/\s+/).length : 0;
        document.getElementById('char-count').innerText = `${words} kata | ${count} karakter`;
    }

    document.addEventListener('DOMContentLoaded', updateCharCount);
</script>
@endsection
