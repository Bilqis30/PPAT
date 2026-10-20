@extends('layouts.app')

@section('title', 'Tulis Materi Baru')
@section('page_title', 'Tulis & Upload Materi Baru')
@section('page_subtitle', 'Tulis materi atau artikel edukasi hukum dan unggah gambar sampul untuk ditampilkan di halaman publik')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.materi.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Materi</span>
        </a>

        <a href="{{ route('materi.index') }}" target="_blank" class="text-xs text-emerald-700 hover:underline flex items-center gap-1 font-medium">
            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            <span>Buka Halaman Materi Publik</span>
        </a>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/60">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                    <i data-lucide="edit-3" class="w-5 h-5 text-emerald-400"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">Formulir Penulisan Materi &amp; Upload</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Isi seluruh informasi materi di bawah ini. Kartu materi akan langsung muncul dengan desain hijau elegan di menu Materi.</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.materi.store') }}" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
            @csrf

            <!-- 1. Judul Materi -->
            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Judul Materi / Artikel <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}" 
                       required
                       placeholder="Contoh: Merawat Jantung Batanghari Sembilan: Kolaborasi Menjaga Hutan..."
                       class="w-full px-4 py-3 bg-white border @error('title') border-rose-300 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                @error('title')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2. Grid: Kategori, Tanggal Terbit & Penulis -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Kategori -->
                <div>
                    <label for="category" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select id="category" 
                            name="category" 
                            required
                            class="w-full px-4 py-2.5 bg-white border @error('category') border-rose-300 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                        <option value="Sosialisasi" {{ old('category') == 'Sosialisasi' ? 'selected' : '' }}>Sosialisasi</option>
                        <option value="Artikel" {{ old('category', 'Artikel') == 'Artikel' ? 'selected' : '' }}>Artikel</option>
                    </select>
                    @error('category')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Terbit -->
                <div>
                    <label for="published_at" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tanggal Terbit <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" 
                           id="published_at" 
                           name="published_at" 
                           value="{{ old('published_at', date('Y-m-d')) }}" 
                           required
                           class="w-full px-4 py-2.5 bg-white border @error('published_at') border-rose-300 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    @error('published_at')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penulis -->
                <div>
                    <label for="author_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nama Penulis / Instansi
                    </label>
                    <input type="text" 
                           id="author_name" 
                           name="author_name" 
                           value="{{ old('author_name', 'PPAT Iwan Kurniawan') }}" 
                           placeholder="PPAT Iwan Kurniawan"
                           class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                </div>
            </div>

            <!-- 3. Fitur Pilihan Background / Foto Sampul Materi -->
            <div class="p-6 bg-slate-50/80 rounded-2xl border border-slate-200 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-200/80 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center">
                            <i data-lucide="image" class="w-4 h-4 text-emerald-700"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Foto Sampul &amp; Background Materi</h4>
                            <p class="text-[11px] text-slate-500">Unggah foto baru dari komputer/HP atau pilih langsung dari galeri foto siap pakai.</p>
                        </div>
                    </div>
                </div>

                <!-- Live Preview & Upload Zone -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
                    <!-- Preview Box -->
                    <div class="md:col-span-5 space-y-2">
                        <span class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Preview Background / Sampul</span>
                        <div class="relative aspect-video rounded-xl overflow-hidden bg-slate-200 border-2 border-slate-300 shadow-sm group flex items-center justify-center">
                            <img id="image-preview" src="{{ asset('images/materi/batanghari_sembilan.jpg') }}" alt="Preview Background" class="w-full h-full object-cover">
                        </div>
                        <p class="text-[10px] text-slate-400 text-center">Tampilan kartu materi di website publik</p>
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
                <input type="hidden" id="preset_cover" name="preset_cover" value="images/materi/batanghari_sembilan.jpg">

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
                                class="preset-btn text-left p-1.5 rounded-xl border-2 border-emerald-500 ring-2 ring-emerald-500/30 bg-white transition focus:outline-none group">
                            <div class="aspect-video rounded-lg overflow-hidden bg-slate-100 mb-1.5 relative">
                                <img src="{{ asset('images/materi/batanghari_sembilan.jpg') }}" alt="Batanghari Sembilan" class="w-full h-full object-cover">
                                <span class="preset-check absolute top-1 right-1 w-4 h-4 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">✓</span>
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

            <!-- 4. Ringkasan Singkat (Excerpt) -->
            <div>
                <label for="excerpt" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Ringkasan Singkat <span class="text-slate-400 font-normal normal-case">(Opsional - cuplikan singkat di bawah judul)</span>
                </label>
                <textarea id="excerpt" 
                          name="excerpt" 
                          rows="2" 
                          placeholder="Ringkasan atau gambaran umum mengenai materi ini..."
                          class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">{{ old('excerpt') }}</textarea>
            </div>

            <!-- 5. Isi Materi / Konten Utama with Formatting Toolbar -->
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
                          placeholder="Tuliskan materi, ulasan, atau artikel Anda secara mendetail di sini..."
                          class="w-full px-4 py-3 bg-white border @error('content') border-rose-300 @else border-slate-200 @enderror rounded-b-xl rounded-t-none text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition leading-relaxed font-sans">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button & Cancel -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.materi.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition shadow-sm">
                    <i data-lucide="check" class="w-4 h-4 text-emerald-400"></i>
                    <span>Publikasikan Materi</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function previewCoverImage(input) {
        const preview = document.getElementById('image-preview');
        const icon = document.getElementById('placeholder-icon');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (icon) icon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
            document.getElementById('preset_cover').value = '';
            document.querySelectorAll('.preset-btn').forEach(btn => {
                btn.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/30');
                btn.querySelector('.preset-check')?.classList.add('hidden');
            });
        }
    }

    function selectPreset(path, buttonElement) {
        document.getElementById('preset_cover').value = path;
        const fileInput = document.getElementById('cover_image');
        if (fileInput) fileInput.value = '';
        const urlInput = document.getElementById('custom_cover_url');
        if (urlInput) urlInput.value = '';

        const preview = document.getElementById('image-preview');
        const icon = document.getElementById('placeholder-icon');
        const assetBase = "{{ asset('') }}";
        preview.src = assetBase + path;
        preview.classList.remove('hidden');
        if (icon) icon.classList.add('hidden');

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
        const icon = document.getElementById('placeholder-icon');
        preview.src = url;
        preview.classList.remove('hidden');
        if (icon) icon.classList.add('hidden');
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
