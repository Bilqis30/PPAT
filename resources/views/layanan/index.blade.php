<!DOCTYPE html>
<html lang="id" class="scroll-smooth bg-[#121314] text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.seo-meta', [
        'title' => 'Panduan Layanan & Syarat Akta PPAT Muara Enim - PPAT Iwan Kurniawan',
        'description' => 'Informasi lengkap syarat dan alur pembuatan Akta Jual Beli (AJB), Hibah, Waris (APHB), APHT, dan balik nama sertifikat tanah di Kantor PPAT Iwan Kurniawan Muara Enim.',
        'keywords' => 'Syarat AJB Muara Enim, Biaya Akta Jual Beli PPAT Muara Enim, Syarat Hibah Tanah Muara Enim, Akta Pembagian Hak Bersama, Balik Nama Sertifikat BPN Muara Enim, PPAT Iwan Kurniawan',
        'canonical' => route('layanan.index'),
        'breadcrumb' => [
            ['name' => 'Beranda', 'url' => route('home')],
            ['name' => 'Panduan Layanan & Syarat Akta', 'url' => route('layanan.index')],
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
                <a href="{{ route('layanan.index') }}" class="text-white font-bold border-b-2 border-red-500 pb-1">Panduan Layanan</a>
                <a href="{{ route('materi.index') }}" class="hover:text-red-400 transition">Materi</a>
                <a href="{{ route('home') }}#kontak" class="hover:text-red-400 transition">Kontak</a>
            </nav>

            <!-- Actions -->
            <div class="hidden sm:flex items-center gap-3">
                <a href="{{ route('home') }}#kontak" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#760604] hover:bg-[#8f0907] text-white text-xs font-bold transition shadow-md border border-red-500/40">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-red-200"></i>
                    <span>Kontak &amp; Lokasi</span>
                </a>
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
                <a href="{{ route('layanan.index') }}" onclick="toggleMobileNav()" class="px-3 py-2 rounded-lg bg-[#760604] text-white font-bold">Panduan Layanan</a>
                <a href="{{ route('materi.index') }}" onclick="toggleMobileNav()" class="px-3 py-2 rounded-lg hover:bg-[#2b2c2d]">Materi</a>
                <a href="{{ route('home') }}#kontak" onclick="toggleMobileNav()" class="px-3 py-2 rounded-lg hover:bg-[#2b2c2d]">Kontak</a>
            </nav>
            <div class="pt-3 border-t border-[#4B4C4D]">
                <a href="{{ route('home') }}#kontak" onclick="toggleMobileNav()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#760604] text-white text-xs font-bold shadow-md">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    <span>Kontak &amp; Lokasi Kantor</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Header Section with Office Photo Background (Foto Kantor PPAT) -->
    <section class="relative py-14 sm:py-24 overflow-hidden" style="background-image: url('{{ asset('images/hero-kantor-ppat.jpg') }}'); background-size: cover; background-position: 85% 40%;">
        <!-- Cinematic Overlay: Gradasi merah di kiri, transparan di tengah, dan gradasi warna merah dikit di sebelah kanan -->
        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(18, 19, 20, 0.95) 0%, rgba(99, 5, 4, 0.75) 35%, rgba(28, 29, 30, 0.35) 54%, transparent 70%, rgba(118, 6, 4, 0.15) 84%, rgba(118, 6, 4, 0.35) 100%);"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#121314] via-transparent to-transparent opacity-85"></div>
        <div class="absolute top-0 inset-x-0 h-16 bg-gradient-to-b from-[#630504]/40 to-transparent pointer-events-none"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#760604] border border-red-400/60 text-xs font-bold text-white mb-4 shadow-lg backdrop-blur-xs">
                    <i data-lucide="clipboard-list" class="w-3.5 h-3.5 text-red-200"></i>
                    <span>Standar Pelayanan PPAT Muara Enim</span>
                </div>
                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight drop-shadow-md">
                    Panduan Cara &amp; Persyaratan Layanan PPAT Muara Enim
                </h1>
                <p class="text-sm sm:text-base text-slate-100 mt-4 leading-relaxed max-w-2xl mx-auto drop-shadow-sm">
                    Ketahui kelengkapan dokumen yang wajib Anda siapkan serta langkah-langkah resmi saat mengajukan permohonan akta tanah di Kantor PPAT Iwan Kurniawan.
                </p>

                <!-- Search Input for Services -->
                <div class="mt-8 max-w-md mx-auto relative">
                    <input type="text" 
                           id="service-search" 
                           onkeyup="filterServices()" 
                           placeholder="Ketik layanan: Jual Beli, Hibah, Waris, Bank..." 
                           class="w-full pl-10 pr-4 py-3 bg-[#2b2c2d] border border-[#4B4C4D] rounded-2xl text-xs sm:text-sm text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-[#1c1d1e] transition shadow-lg">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                </div>
            </div>

            <!-- Quick Jump Badges -->
            <div class="mt-6 flex flex-wrap items-center justify-center gap-2 pt-2">
                <span class="text-xs text-slate-200 font-medium mr-2">Pilih Layanan:</span>
                @foreach($services as $srv)
                    <a href="#{{ $srv['id'] }}" 
                       class="px-3 py-1.5 rounded-xl bg-[#2b2c2d]/80 hover:bg-[#760604] text-white text-xs font-semibold transition border border-[#4B4C4D] hover:border-red-400 backdrop-blur-xs">
                        {{ $srv['title'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Services Guide List -->
    <main class="flex-1 py-10 sm:py-16 bg-[#121314]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10" id="services-container">
            @foreach($services as $index => $service)
                <!-- Service Block Card -->
                <div id="{{ $service['id'] }}" class="service-card scroll-mt-28 bg-[#1c1d1e] rounded-3xl border border-[#4B4C4D] hover:border-[#760604] shadow-xl overflow-hidden transition-all duration-300">
                    
                    <!-- Header of Service -->
                    <div class="p-6 sm:p-8 border-b border-[#2d2e2f] bg-[#232425] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="w-12 h-12 rounded-2xl bg-[#760604] text-white flex items-center justify-center shrink-0 shadow-md">
                                <span class="font-extrabold text-base">{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#760604] text-white border border-red-400/50">
                                        {{ $service['badge'] }}
                                    </span>
                                    <span class="text-xs text-slate-500">&bull;</span>
                                    <span class="text-xs text-slate-300 font-medium">Estimasi: {{ $service['estimated_time'] }}</span>
                                </div>
                                <h3 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                                    {{ $service['title'] }}
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
                                    {{ $service['description'] }}
                                </p>
                            </div>
                        </div>

                        @auth
                            @if(Auth::user()->isAdmin() && !empty($service['model_id']))
                                <div class="sm:self-start shrink-0">
                                    <a href="{{ route('admin.layanan.edit', $service['model_id']) }}" 
                                       title="Edit Data Layanan Ini di Web Admin"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-amber-950/90 hover:bg-amber-900 text-amber-200 border border-amber-700 text-xs font-semibold transition shadow-md">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        <span>Edit di Admin</span>
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <!-- Body Content -->
                    <div class="p-6 sm:p-8 space-y-8">
                        
                        <!-- 1. Langkah-Langkah / Cara Pengajuan -->
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-red-400 mb-4 flex items-center gap-2">
                                <i data-lucide="git-commit" class="w-4 h-4 text-red-400"></i>
                                <span>Alur &amp; Langkah Pengajuan (Cara Kerja)</span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                                @foreach($service['steps'] as $stepIndex => $step)
                                    <div class="p-3.5 rounded-2xl bg-[#2b2c2d] border border-[#4B4C4D]/60 flex items-start gap-3">
                                        <div class="w-6 h-6 rounded-lg bg-[#760604] text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                            {{ $stepIndex + 1 }}
                                        </div>
                                        <p class="text-xs text-slate-200 leading-relaxed font-medium">
                                            {{ $step }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2. Persyaratan Dokumen -->
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-red-400 mb-4 flex items-center gap-2">
                                <i data-lucide="check-square" class="w-4 h-4 text-red-400"></i>
                                <span>Daftar Dokumen Persyaratan Wajib</span>
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Kolom Pihak Pertama -->
                                <div class="p-5 rounded-2xl bg-[#2b2c2d] border border-[#4B4C4D]/80 space-y-3">
                                    <div class="flex items-center gap-2 pb-2 border-b border-[#4B4C4D]">
                                        <i data-lucide="user" class="w-4 h-4 text-red-300"></i>
                                        <span class="text-xs font-bold text-white uppercase tracking-wider">
                                            {{ $service['id'] === 'hibah' ? 'Pihak Pemberi Hibah' : ($service['id'] === 'waris' ? 'Pihak Pewaris / Objek' : ($service['id'] === 'apht' ? 'Pihak Debitur / Pemilik Jaminan' : 'Pihak Penjual / Pemilik Tanah')) }}
                                        </span>
                                    </div>
                                    <ul class="space-y-2 text-xs text-slate-200">
                                        @foreach($service['requirements_penjual'] as $req)
                                            <li class="flex items-start gap-2">
                                                <i data-lucide="check-circle" class="w-3.5 h-3.5 text-red-400 shrink-0 mt-0.5"></i>
                                                <span class="leading-relaxed">{{ $req }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- Kolom Pihak Kedua -->
                                <div class="p-5 rounded-2xl bg-[#2b2c2d] border border-[#4B4C4D]/80 space-y-3">
                                    <div class="flex items-center gap-2 pb-2 border-b border-[#4B4C4D]">
                                        <i data-lucide="user-check" class="w-4 h-4 text-red-300"></i>
                                        <span class="text-xs font-bold text-white uppercase tracking-wider">
                                            {{ $service['id'] === 'hibah' ? 'Pihak Penerima Hibah' : ($service['id'] === 'waris' ? 'Seluruh Ahli Waris' : ($service['id'] === 'apht' ? 'Pihak Kreditur / Bank' : 'Pihak Pembeli')) }}
                                        </span>
                                    </div>
                                    <ul class="space-y-2 text-xs text-slate-200">
                                        @foreach($service['requirements_pembeli'] as $req)
                                            <li class="flex items-start gap-2">
                                                <i data-lucide="check-circle" class="w-3.5 h-3.5 text-red-400 shrink-0 mt-0.5"></i>
                                                <span class="leading-relaxed">{{ $req }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <!-- Help Banner at Bottom -->
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-14">
            <div class="bg-[#1c1d1e] text-white rounded-3xl p-6 sm:p-10 flex flex-col md:flex-row items-center justify-between gap-6 border border-[#760604] shadow-2xl">
                <div class="space-y-2 text-center md:text-left">
                    <span class="inline-block px-3 py-1 rounded-full bg-[#760604] text-white text-xs font-semibold">
                        Layanan Konsultasi Berkas
                    </span>
                    <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                        Masih Ragu dengan Kelengkapan Berkas Anda?
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-xl leading-relaxed">
                        Anda dapat membawa berkas dokumen sertifikat terlebih dahulu ke kantor kami untuk pengecekan awal sebelum penandatanganan akta resmi.
                    </p>
                </div>
                <a href="{{ route('home') }}#kontak" 
                   class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-[#760604] hover:bg-[#8f0907] text-white text-xs sm:text-sm font-bold transition shadow-lg shrink-0 border border-red-500/50">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    <span>Kunjungi Kantor PPAT</span>
                </a>
            </div>
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
                        Pejabat Pembuat Akta Tanah (PPAT) Kabupaten Muara Enim berdasarkan SK Menteri ATR/BPN RI No. 37/SK-HR.03.04.PPAT/III/2023. Melayani pembuatan akta tanah dengan standar pelayanan profesional dan tertib administrasi kearsipan.
                    </p>
                </div>

                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-3">Tautan Halaman</h5>
                    <ul class="space-y-2 text-xs text-red-100">
                        <li><a href="{{ route('home') }}" class="hover:text-white hover:underline transition">Beranda Utama</a></li>
                        <li><a href="{{ route('home') }}#profil" class="hover:text-white hover:underline transition">Profil &amp; SK Legalitas</a></li>
                        <li><a href="{{ route('layanan.index') }}" class="text-white font-bold hover:underline transition">Panduan Layanan &amp; Syarat</a></li>
                        <li><a href="{{ route('materi.index') }}" class="hover:text-white hover:underline transition">Materi &amp; Artikel</a></li>
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

        function filterServices() {
            const query = document.getElementById('service-search').value.toLowerCase();
            const cards = document.querySelectorAll('.service-card');
            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                if (text.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
