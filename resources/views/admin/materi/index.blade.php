@extends('layouts.app')

@section('title', 'Kelola Materi & Artikel')
@section('page_title', 'Manajemen Materi & Artikel')
@section('page_subtitle', 'Tulis, unggah gambar sampul, dan kelola publikasi materi edukasi')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Stats Bar -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <!-- Quick Info -->
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-white rounded-xl border border-slate-200 shadow-sm text-xs">
                <span class="text-slate-400">Total Materi:</span>
                <strong class="text-slate-900 ml-1">{{ $materis->total() }}</strong>
            </div>
            <a href="{{ route('materi.index') }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition">
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                <span>Lihat Tampilan Publik</span>
            </a>
        </div>

        <!-- Write New Button -->
        <a href="{{ route('admin.materi.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-semibold transition shadow-sm">
            <i data-lucide="plus-circle" class="w-4 h-4 text-emerald-400"></i>
            <span>Tulis Materi Baru</span>
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">&times;</button>
        </div>
    @endif

    <!-- Search and Category Filter -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-3">
        <form action="{{ route('admin.materi.index') }}" method="GET" class="w-full md:w-80 relative">
            <input type="text" 
                   name="q" 
                   value="{{ request('q') }}" 
                   placeholder="Cari judul materi..." 
                   class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
            @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif
        </form>

        <!-- Category Filter Tabs -->
        <div class="flex items-center gap-1.5 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
            <a href="{{ route('admin.materi.index', array_filter(['q' => request('q')])) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ !request('kategori') ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </a>
            <a href="{{ route('admin.materi.index', array_filter(['q' => request('q'), 'kategori' => 'Sosialisasi'])) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request('kategori') == 'Sosialisasi' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Sosialisasi
            </a>
            <a href="{{ route('admin.materi.index', array_filter(['q' => request('q'), 'kategori' => 'Artikel'])) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request('kategori') == 'Artikel' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Artikel
            </a>

            @if(request()->hasAny(['q', 'kategori']))
                <a href="{{ route('admin.materi.index') }}" class="text-xs text-rose-600 hover:underline flex items-center gap-1 ml-2 font-medium">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    <span>Reset</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Table of Materials -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-bold">
                    <tr>
                        <th class="px-6 py-4">Sampul</th>
                        <th class="px-6 py-4">Judul &amp; Excerpt</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Tanggal Terbit</th>
                        <th class="px-6 py-4">Penulis</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($materis as $materi)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Thumbnail (Clickable to change BG) -->
                            <td class="px-6 py-4">
                                <button type="button" 
                                        onclick="openChangeBgModal('{{ $materi->id }}', '{{ addslashes($materi->title) }}', '{{ $materi->cover_image_url }}', '{{ route('admin.materi.update-cover', $materi) }}')"
                                        title="Klik untuk Ganti Background / Sampul"
                                        class="relative w-16 h-12 rounded-lg bg-slate-100 overflow-hidden border border-slate-200 shrink-0 group block text-left focus:outline-none">
                                    <img src="{{ $materi->cover_image_url }}" alt="{{ $materi->title }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-bold">
                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                    </div>
                                </button>
                            </td>

                            <!-- Title -->
                            <td class="px-6 py-4 max-w-md">
                                <a href="{{ route('materi.show', $materi->slug) }}" target="_blank" class="font-bold text-slate-900 hover:text-emerald-700 transition line-clamp-1">
                                    {{ $materi->title }}
                                </a>
                                @if($materi->excerpt)
                                    <p class="text-xs text-slate-400 mt-1 line-clamp-1">{{ $materi->excerpt }}</p>
                                @endif
                            </td>

                            <!-- Category -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold {{ $materi->category == 'Sosialisasi' ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' }}">
                                    {{ $materi->category }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-600">
                                {{ $materi->formatted_date }}
                            </td>

                            <!-- Author -->
                            <td class="px-6 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $materi->author_name ?? 'Admin PPAT' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" 
                                            onclick="openChangeBgModal('{{ $materi->id }}', '{{ addslashes($materi->title) }}', '{{ $materi->cover_image_url }}', '{{ route('admin.materi.update-cover', $materi) }}')"
                                            title="Ganti Background / Foto Sampul Materi Ini"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-semibold transition border border-emerald-200 shadow-2xs">
                                        <i data-lucide="image" class="w-3.5 h-3.5 text-emerald-600"></i>
                                        <span>Ganti BG</span>
                                    </button>
                                    <a href="{{ route('admin.materi.edit', $materi) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition shadow-xs">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-emerald-400"></i>
                                        <span>Edit Tulisan</span>
                                    </a>
                                    <a href="{{ route('materi.show', $materi->slug) }}" target="_blank" 
                                       title="Lihat Tampilan Publik" 
                                       class="p-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.materi.destroy', $materi) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Materi" 
                                                class="p-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="book-open" class="w-8 h-8 text-slate-300 mb-2"></i>
                                    <p class="font-medium text-sm text-slate-600">Belum ada materi atau artikel</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Klik tombol "Tulis Materi Baru" di atas untuk menambahkan materi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($materis->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $materis->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Quick Ganti Background / Foto Sampul Materi -->
<div id="change-bg-modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full border border-slate-200 shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center">
                    <i data-lucide="image" class="w-4 h-4 text-emerald-700"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900">Ganti Background Materi</h3>
                    <p id="modal-materi-title" class="text-xs text-slate-500 truncate max-w-xs"></p>
                </div>
            </div>
            <button type="button" onclick="closeChangeBgModal()" class="text-slate-400 hover:text-slate-700 p-1 rounded-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="modal-bg-form" method="POST" action="" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf

            <!-- Preview Image -->
            <div>
                <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Preview Background Saat Ini / Terpilih</span>
                <div class="aspect-video w-full rounded-xl overflow-hidden bg-slate-100 border border-slate-200 relative">
                    <img id="modal-preview-img" src="" alt="Preview" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Upload File Baru -->
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Pilihan 1: Upload File Gambar Baru
                </label>
                <input type="file" 
                       id="modal_cover_file" 
                       name="cover_image" 
                       accept="image/jpeg,image/png,image/jpg,image/webp" 
                       onchange="previewModalFile(this)"
                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
            </div>

            <!-- Hidden Preset Input -->
            <input type="hidden" id="modal_preset_cover" name="preset_cover" value="">

            <!-- Pilihan Preset 1-Klik -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Pilihan 2: Atau Pilih Dari Foto Koleksi Siap Pakai
                </label>
                <div class="grid grid-cols-4 gap-2">
                    <button type="button" onclick="selectModalPreset('images/hero-kantor-ppat.jpg', this)" class="modal-preset-btn p-1 border-2 border-slate-200 hover:border-emerald-500 rounded-lg bg-slate-50 text-left transition focus:outline-none">
                        <div class="aspect-video rounded overflow-hidden bg-slate-200 mb-1">
                            <img src="{{ asset('images/hero-kantor-ppat.jpg') }}" alt="Kantor" class="w-full h-full object-cover">
                        </div>
                        <span class="block text-[10px] font-bold text-slate-700 truncate">Kantor PPAT</span>
                    </button>
                    <button type="button" onclick="selectModalPreset('images/materi/batanghari_sembilan.jpg', this)" class="modal-preset-btn p-1 border-2 border-slate-200 hover:border-emerald-500 rounded-lg bg-slate-50 text-left transition focus:outline-none">
                        <div class="aspect-video rounded overflow-hidden bg-slate-200 mb-1">
                            <img src="{{ asset('images/materi/batanghari_sembilan.jpg') }}" alt="Danau" class="w-full h-full object-cover">
                        </div>
                        <span class="block text-[10px] font-bold text-slate-700 truncate">Danau Hutan</span>
                    </button>
                    <button type="button" onclick="selectModalPreset('images/materi/agroforestri_tambang.jpg', this)" class="modal-preset-btn p-1 border-2 border-slate-200 hover:border-emerald-500 rounded-lg bg-slate-50 text-left transition focus:outline-none">
                        <div class="aspect-video rounded overflow-hidden bg-slate-200 mb-1">
                            <img src="{{ asset('images/materi/agroforestri_tambang.jpg') }}" alt="Agroforestri" class="w-full h-full object-cover">
                        </div>
                        <span class="block text-[10px] font-bold text-slate-700 truncate">Lahan Hijau</span>
                    </button>
                    <button type="button" onclick="selectModalPreset('images/hero-forest-bg.jpg', this)" class="modal-preset-btn p-1 border-2 border-slate-200 hover:border-emerald-500 rounded-lg bg-slate-50 text-left transition focus:outline-none">
                        <div class="aspect-video rounded overflow-hidden bg-slate-200 mb-1">
                            <img src="{{ asset('images/hero-forest-bg.jpg') }}" alt="Hutan" class="w-full h-full object-cover">
                        </div>
                        <span class="block text-[10px] font-bold text-slate-700 truncate">Hutan Tropis</span>
                    </button>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeChangeBgModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-xs">
                    <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-400"></i>
                    <span>Simpan Background</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openChangeBgModal(id, title, currentImgUrl, actionUrl) {
        document.getElementById('modal-materi-title').innerText = title;
        document.getElementById('modal-preview-img').src = currentImgUrl;
        document.getElementById('modal-bg-form').action = actionUrl;
        document.getElementById('modal_preset_cover').value = '';
        document.getElementById('modal_cover_file').value = '';
        document.querySelectorAll('.modal-preset-btn').forEach(b => b.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/30'));
        document.getElementById('change-bg-modal').classList.remove('hidden');
    }

    function closeChangeBgModal() {
        document.getElementById('change-bg-modal').classList.add('hidden');
    }

    function previewModalFile(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('modal-preview-img').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
            document.getElementById('modal_preset_cover').value = '';
            document.querySelectorAll('.modal-preset-btn').forEach(b => b.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/30'));
        }
    }

    function selectModalPreset(path, btn) {
        document.getElementById('modal_preset_cover').value = path;
        document.getElementById('modal_cover_file').value = '';
        const assetBase = "{{ asset('') }}";
        document.getElementById('modal-preview-img').src = assetBase + path;
        document.querySelectorAll('.modal-preset-btn').forEach(b => b.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/30'));
        btn.classList.add('border-emerald-500', 'ring-2', 'ring-emerald-500/30');
    }
</script>
@endsection
