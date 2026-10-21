<!DOCTYPE html>
<html lang="id" class="scroll-smooth bg-[#121314] text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.seo-meta', [
        'title' => 'Materi & Edukasi Hukum Pertanahan - PPAT Iwan Kurniawan Muara Enim',
        'description' => 'Kumpulan artikel dan materi edukasi hukum pertanahan, agraria, tata cara pembuatan akta tanah, dan informasi legalitas dari Kantor PPAT Iwan Kurniawan Muara Enim.',
        'keywords' => 'Materi Hukum Pertanahan, Artikel PPAT Muara Enim, Edukasi Hukum Agraria, Sertifikat Tanah Muara Enim, Pembuatan Akta PPAT, Kantor PPAT Iwan Kurniawan',
        'canonical' => route('materi.index'),
        'breadcrumb' => [
            ['name' => 'Beranda', 'url' => route('home')],
            ['name' => 'Materi & Edukasi Pertanahan', 'url' => route('materi.index')],
        ]
    ])

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN) & Configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind-config.js') }}"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-[#760604] selection:text-white bg-[#121314] text-white">

    <!-- Top Notice / Official Legal Bar -->
    <div class="bg-gradient-to-r from-[#630504] via-[#450302] to-[#200201] text-red-100 text-xs py-2 px-4 border-b border-[#760604]/60">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-1 text-center sm:text-left">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
                <span class="font-medium text-white">SK Menteri ATR/BPN RI No: 37/SK-HR.03.04.PPAT/III/2023</span>
            </div>
            <div class="text-[11px] text-red-200">
                Wilayah Kerja: <strong class="text-white font-bold">Kabupaten Muara Enim</strong>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <header class="sticky top-0 z-40 bg-gradient-to-r from-[#630504]/95 via-[#2d0808]/95 to-[#1c1d1e]/95 backdrop-blur-md border-b border-[#760604]/60 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo & Office Identity -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group" title="Kantor PPAT Iwan Kurniawan, SH., MH., M.Kn Muara Enim">
                <img src="{{ asset('images/logo-ppat.jpg') }}" alt="Logo Resmi IPPAT PPAT Iwan Kurniawan Muara Enim" class="w-11 h-11 object-contain shrink-0 rounded-full shadow-md border border-[#760604]/50 group-hover:scale-105 transition bg-white p-0.5">
                <div class="leading-tight">
                    <span class="block font-bold text-sm sm:text-base text-white tracking-tight">PPAT Iwan Kurniawan, SH., MH., M.Kn</span>
                    <p class="text-xs text-slate-300 font-medium">Pejabat Pembuat Akta Tanah &bull; Kab. Muara Enim</p>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-slate-200">
                <a href="{{ route('home') }}" class="hover:text-red-400 transition">Beranda</a>
                <a href="{{ route('home') }}#profil" class="hover:text-red-400 transition">Profil Kantor</a>
                <a href="{{ route('layanan.index') }}" class="hover:text-red-400 transition">Panduan Layanan</a>
                <a href="{{ route('materi.index') }}" class="text-white font-bold border-b-2 border-red-500 pb-1">Materi</a>
                <a href="{{ route('home') }}#kontak" class="hover:text-red-400 transition">Kontak</a>
            </nav>

            <!-- Actions -->
            <div class="hidden sm:flex items-center gap-3">
                @auth
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('pimpinan.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#2b2c2d] hover:bg-[#3a3b3c] text-white text-xs font-semibold transition border border-[#4B4C4D]">
                        <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 text-red-300"></i>
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('home') }}#kontak" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#760604] hover:bg-[#8f0907] text-white text-xs font-bold transition shadow-md border border-red-500/40">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-red-200"></i>
                        <span>Hubungi Kantor</span>
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button onclick="toggleMobileNav()" class="md:hidden p-2 text-slate-300 hover:text-white rounded-lg hover:bg-[#2d2e2f]" aria-label="Toggle Navigation">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-nav" class="hidden md:hidden border-t border-[#760604]/50 bg-gradient-to-b from-[#2d0808] to-[#1c1d1e] px-4 py-4 space-y-3 shadow-2xl">
            <nav class="flex flex-col space-y-2 text-sm font-semibold text-slate-200">
                <a href="{{ route('home') }}" onclick="toggleMobileNav()" class="px-3 py-2 rounded-lg hover:bg-[#2b2c2d]">Beranda</a>
                <a href="{{ route('home') }}#profil" onclick="toggleMobileNav()" class="px-3 py-2 rounded-lg hover:bg-[#2b2c2d]">Profil Kantor</a>
                <a href="{{ route('layanan.index') }}" onclick="toggleMobileNav()" class="px-3 py-2 rounded-lg hover:bg-[#2b2c2d]">Panduan Layanan</a>
                <a href="{{ route('materi.index') }}" onclick="toggleMobileNav()" class="px-3 py-2 rounded-lg bg-[#760604] text-white font-bold">Materi</a>
                <a href="{{ route('home') }}#kontak" onclick="toggleMobileNav()" class="px-3 py-2 rounded-lg hover:bg-[#2b2c2d]">Kontak</a>
            </nav>
            <div class="pt-3 border-t border-[#4B4C4D]">
                @auth
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('pimpinan.dashboard') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#2b2c2d] text-white text-xs font-semibold">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 text-red-400"></i>
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('home') }}#kontak" onclick="toggleMobileNav()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#760604] text-white text-xs font-bold">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        <span>Hubungi Kantor</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Page Header Section with Office Photo Background (Foto Kantor PPAT) -->
    <section class="relative py-14 sm:py-24 overflow-hidden" style="background-image: url('{{ asset('images/hero-kantor-ppat.jpg') }}'); background-size: cover; background-position: 85% 40%;">
        <!-- Cinematic Overlay: Gradasi merah di kiri, transparan di tengah, dan gradasi warna merah dikit di sebelah kanan -->
        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(18, 19, 20, 0.95) 0%, rgba(99, 5, 4, 0.75) 35%, rgba(28, 29, 30, 0.35) 54%, transparent 70%, rgba(118, 6, 4, 0.15) 84%, rgba(118, 6, 4, 0.35) 100%);"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#121314] via-transparent to-transparent opacity-85"></div>
        <div class="absolute top-0 inset-x-0 h-16 bg-gradient-to-b from-[#630504]/40 to-transparent pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#760604] border border-red-400/60 text-xs font-bold text-white mb-4 shadow-lg backdrop-blur-xs">
                    <i data-lucide="book-open" class="w-3.5 h-3.5 text-red-200"></i>
                    <span>Publikasi &amp; Edukasi Hukum Pertanahan</span>
                </div>
                <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight drop-shadow-md">
                    Materi &amp; Edukasi Hukum Pertanahan PPAT Muara Enim
                </h1>
                <p class="text-sm sm:text-base text-slate-100 mt-4 leading-relaxed max-w-2xl drop-shadow-sm">
                    Kumpulan wawasan, edukasi hukum pertanahan, kajian lingkungan, serta dokumentasi materi resmi dari Kantor PPAT Iwan Kurniawan.
                </p>
            </div>

            <!-- Search and Filter Bar -->
            <div class="mt-8 pt-2 flex flex-col sm:flex-row gap-4 items-center justify-between">
                <form action="{{ route('materi.index') }}" method="GET" class="w-full sm:w-80 relative">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari materi atau artikel..." 
                           class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-[#2b2c2d] border border-[#4B4C4D] text-white placeholder-slate-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-[#1c1d1e] transition shadow-md">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                </form>

                <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto pb-1">
                    <a href="{{ route('materi.index') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ !request('kategori') ? 'bg-[#760604] text-white shadow-md border border-red-400/50' : 'bg-[#2b2c2d] text-slate-300 hover:bg-[#760604] hover:text-white border border-[#4B4C4D]' }}">
                        Semua ({{ \App\Models\Materi::count() }})
                    </a>
                    <a href="{{ route('materi.index', ['kategori' => 'Artikel']) }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ request('kategori') == 'Artikel' ? 'bg-[#760604] text-white shadow-md border border-red-400/50' : 'bg-[#2b2c2d] text-slate-300 hover:bg-[#760604] hover:text-white border border-[#4B4C4D]' }}">
                        Artikel
                    </a>
                    <a href="{{ route('materi.index', ['kategori' => 'Sosialisasi']) }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ request('kategori') == 'Sosialisasi' ? 'bg-[#760604] text-white shadow-md border border-red-400/50' : 'bg-[#2b2c2d] text-slate-300 hover:bg-[#760604] hover:text-white border border-[#4B4C4D]' }}">
                        Sosialisasi
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content: Materi Cards Grid -->
    <main class="flex-1 py-10 sm:py-16 bg-[#121314]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($materis->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8">
                    @foreach($materis as $item)
                        <!-- Card Container -->
                        <article class="bg-[#1c1d1e] rounded-2xl overflow-hidden border border-[#4B4C4D] hover:border-[#760604] hover:shadow-2xl transition-all duration-300 flex flex-col justify-between group">
                            
                            <!-- Cover Image -->
                            <div class="relative w-full h-40 sm:h-44 overflow-hidden bg-[#2b2c2d]">
                                <img src="{{ $item->cover_image_url }}" 
                                     alt="{{ $item->title }}" 
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>

                            <!-- Card Body -->
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-white text-sm sm:text-base font-bold leading-snug tracking-tight group-hover:text-red-300 transition">
                                        <span class="text-red-400 font-extrabold">{{ $item->category ?? 'Artikel' }} :</span>
                                        <span>{{ $item->title }}</span>
                                    </h3>
                                </div>

                                <!-- Card Footer: Date & Link -->
                                <div class="mt-4 pt-3 border-t border-[#2b2c2d] flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-1.5 text-slate-400 font-medium">
                                        <span>🗓️</span>
                                        <span>{{ $item->formatted_date }}</span>
                                    </div>

                                    <div class="flex items-center gap-2.5">
                                        @auth
                                            @if(Auth::user()->isAdmin())
                                                <a href="{{ route('admin.materi.edit', $item) }}" 
                                                   title="Edit Tulisan Materi Ini"
                                                   class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-200 bg-amber-950/80 hover:bg-amber-900 px-2 py-0.5 rounded-md border border-amber-800 transition">
                                                    <i data-lucide="edit-3" class="w-3 h-3"></i>
                                                    <span>Edit</span>
                                                </a>
                                            @endif
                                        @endauth

                                        <a href="{{ route('materi.show', $item->slug) }}" 
                                           class="inline-flex items-center gap-1 text-red-400 hover:text-red-300 font-bold transition group-hover:translate-x-0.5">
                                            <span>Baca</span>
                                            <span class="text-xs">&rarr;</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($materis->hasPages())
                    <div class="mt-12">
                        {{ $materis->links() }}
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="bg-[#1c1d1e] rounded-3xl p-12 text-center max-w-xl mx-auto border border-[#4B4C4D] shadow-xl">
                    <div class="w-14 h-14 rounded-2xl bg-[#760604] text-white flex items-center justify-center mx-auto mb-4 shadow-md">
                        <i data-lucide="book-open" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Belum Ada Materi Diterbitkan</h3>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-sm mx-auto">
                        Belum ada materi atau artikel yang dipublikasikan saat ini. Silakan kunjungi kembali nanti.
                    </p>
                </div>
            @endif

        </div>
    </main>

    <!-- Footer -->
    <footer id="kontak" class="bg-[#760604] text-white border-t-2 border-red-900 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-ppat.jpg') }}" alt="Logo IPPAT" class="w-10 h-10 object-contain shrink-0 rounded-full shadow-md bg-white p-0.5">
                        <h4 class="font-extrabold text-white text-base">PPAT Iwan Kurniawan, SH., MH., M.Kn</h4>
                    </div>
                    <p class="text-xs text-red-100 leading-relaxed max-w-md">
                        Pejabat Pembuat Akta Tanah (PPAT) Kabupaten Muara Enim berdasarkan SK Menteri Agraria dan Tata Ruang / Kepala BPN RI No. 37/SK-HR.03.04.PPAT/III/2023. Melayani pembuatan akta tanah dengan standar pelayanan profesional dan tertib administrasi.
                    </p>
                </div>

                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-3">Tautan Halaman</h5>
                    <ul class="space-y-2 text-xs text-red-100">
                        <li><a href="{{ route('home') }}" class="hover:text-white hover:underline transition">Beranda Utama</a></li>
                        <li><a href="{{ route('home') }}#profil" class="hover:text-white hover:underline transition">Profil &amp; SK Legalitas</a></li>
                        <li><a href="{{ route('layanan.index') }}" class="hover:text-white hover:underline transition">Panduan Layanan &amp; Syarat</a></li>
                        <li><a href="{{ route('materi.index') }}" class="text-white font-bold hover:underline transition">Materi &amp; Artikel</a></li>
                        <li><a href="{{ route('home') }}#kontak" class="hover:text-white hover:underline transition">Kontak &amp; Pelayanan</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-3">Kontak &amp; Alamat</h5>
                    <address class="not-italic text-xs text-red-100 space-y-2">
                        <p class="leading-relaxed">
                            Jl. Tembesu No. 2 RT.01/03 Pasar I Pelita Sari (Depan SMAN 1 Muara Enim), Kec. Muara Enim, Kab. Muara Enim, 31311
                        </p>
                        <p><strong class="text-white">Email:</strong> Iwannotppat1@gmail.com</p>
                    </address>
                </div>
            </div>

            <div class="mt-12 pt-6 border-t border-red-900/70 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-red-200">
                <p>&copy; {{ date('Y') }} Kantor PPAT Iwan Kurniawan, SH., MH., M.Kn. Hak Cipta Dilindungi.</p>
                <p class="flex items-center gap-2">
                    <span class="text-white font-medium">Kabupaten Muara Enim</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Interactive Scripts -->
    <script>
        lucide.createIcons();

        function toggleMobileNav() {
            const nav = document.getElementById('mobile-nav');
            if (nav) {
                nav.classList.toggle('hidden');
            }
        }
    </script>
</body>
</html>
