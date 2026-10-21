<!DOCTYPE html>
<html lang="id" class="scroll-smooth bg-[#121314] text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('partials.seo-meta', [
        'title' => 'PPAT Iwan Kurniawan, SH., MH., M.Kn - Kantor PPAT Muara Enim Resmi',
        'description' => 'Kantor Resmi PPAT Iwan Kurniawan, SH., MH., M.Kn di Kabupaten Muara Enim. SK Menteri ATR/BPN RI No: 37/SK-HR.03.04.PPAT/III/2023. Melayani pembuatan Akta Jual Beli (AJB), Hibah, APHB, APHT, dan pengurusan sertifikat tanah.',
        'keywords' => 'PPAT Muara Enim, Kantor PPAT Muara Enim, PPAT Iwan Kurniawan, Notaris PPAT Muara Enim, Akta Jual Beli Tanah Muara Enim, AJB Muara Enim, Akta Hibah Tanah, Akta Pembagian Hak Bersama, Balik Nama Sertifikat BPN Muara Enim, SK Menteri ATR BPN Muara Enim',
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
<body class="min-h-screen flex flex-col antialiased bg-[#121314] text-white selection:bg-[#760604] selection:text-white">

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
                    <span class="block font-extrabold text-sm sm:text-base text-white tracking-tight">PPAT Iwan Kurniawan, SH., MH., M.Kn</span>
                    <p class="text-xs text-slate-300 font-medium">Pejabat Pembuat Akta Tanah &bull; Kab. Muara Enim</p>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-slate-200">
                <a href="#beranda" class="text-white font-bold border-b-2 border-red-500 pb-1">Beranda</a>
                <a href="#profil" class="hover:text-red-400 transition">Profil Kantor</a>
                <a href="{{ route('layanan.index') }}" class="hover:text-red-400 transition">Panduan Layanan</a>
                <a href="{{ route('materi.index') }}" class="hover:text-red-400 transition">Materi</a>
                <a href="#kontak" class="hover:text-red-400 transition">Kontak</a>
            </nav>

            <!-- Public CTA Action -->
            <div class="hidden sm:flex items-center gap-3">
                <a href="#kontak" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#760604] hover:bg-[#8f0907] text-white text-xs font-bold transition shadow-md border border-red-500/40">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-red-200"></i>
                    <span>Hubungi Kantor</span>
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
                <a href="#beranda" onclick="toggleMobileNav()" class="px-3 py-2 rounded-lg bg-[#760604] text-white font-bold">Beranda</a>
                <a href="#profil" onclick="toggleMobileNav()" class="px-3 py-2 rounded-lg hover:bg-[#2b2c2d]">Profil Kantor</a>
                <a href="{{ route('layanan.index') }}" onclick="toggleMobileNav()" class="px-3 py-2 rounded-lg hover:bg-[#2b2c2d] text-red-300">Panduan Layanan</a>
                <a href="{{ route('materi.index') }}" onclick="toggleMobileNav()" class="px-3 py-2 rounded-lg hover:bg-[#2b2c2d]">Materi</a>
                <a href="#kontak" onclick="toggleMobileNav()" class="px-3 py-2 rounded-lg hover:bg-[#2b2c2d]">Kontak</a>
            </nav>
            <div class="pt-3 border-t border-[#4B4C4D]">
                <a href="#kontak" onclick="toggleMobileNav()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#760604] text-white text-xs font-bold shadow-md">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    <span>Hubungi Kantor</span>
                </a>
            </div>
        </div>
    </header>

    <!-- 1. Hero Section With Photo Background (Foto Kantor PPAT) -->
    <section id="beranda" class="relative min-h-[calc(100vh-112px)] flex items-center py-16 sm:py-24 overflow-hidden" style="background-image: url('{{ asset('images/hero-kantor-ppat.jpg') }}'); background-size: cover; background-position: 85% 40%;">
        <!-- Gradient Overlay: Gradasi merah di kiri, transparan di tengah agar plang terbaca, dan gradasi warna merah dikit di sebelah kanan -->
        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(18, 19, 20, 0.96) 0%, rgba(99, 5, 4, 0.70) 30%, rgba(28, 29, 30, 0.35) 52%, transparent 68%, rgba(118, 6, 4, 0.15) 84%, rgba(118, 6, 4, 0.35) 100%);"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#121314] via-transparent to-transparent opacity-85"></div>
        <div class="absolute top-0 inset-x-0 h-16 bg-gradient-to-b from-[#630504]/40 to-transparent pointer-events-none"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
            <div class="max-w-3xl">

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight sm:leading-[1.12] drop-shadow-[0_4px_12px_rgba(0,0,0,0.8)]">
                    Kantor PPAT Iwan Kurniawan, SH., MH., M.Kn
                </h1>
                <p class="mt-4 text-base sm:text-lg text-slate-200 font-medium leading-relaxed max-w-2xl drop-shadow-md">
                    Pejabat Pembuat Akta Tanah Resmi Wilayah Kabupaten Muara Enim. Melayani pengurusan Akta Jual Beli (AJB), Hibah, Waris, Pembagian Hak Bersama (APHB), APHT Perbankan, dan pengurusan sertifikat tanah BPN secara profesional dan amanah.
                </p>

                <!-- Action Links -->
                <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                    <a href="#profil" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-[#760604] hover:bg-[#8f0907] text-white text-sm font-bold transition shadow-xl border border-red-400/50 group">
                        <i data-lucide="user-check" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                        <span> Profil Kantor</span>
                    </a>
                    <a href="{{ route('materi.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-[#4B4C4D]/80 hover:bg-[#4B4C4D] text-white text-sm font-semibold transition border border-white/30 backdrop-blur-sm shadow-md group">
                        <i data-lucide="book-open" class="w-4 h-4 text-red-300 group-hover:scale-110 transition-transform"></i>
                        <span> Materi &amp; Artikel</span>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. Section Profil Kantor Resmi -->
    <section id="profil" class="pt-8 pb-16 sm:pt-10 sm:pb-24 bg-[#1c1d1e] border-b border-[#2d2e2f]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="inline-block px-6 py-2 sm:px-8 sm:py-2.5 rounded-full bg-[#760604] text-white text-lg sm:text-2xl font-extrabold uppercase tracking-widest mb-3 shadow-lg border border-red-500/40">
                    Legalitas
                </span>
            </div>

            <!-- Profile Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1: Nama & Wilayah Kerja -->
                <div class="bg-[#2b2c2d] rounded-2xl p-6 sm:p-7 border border-[#4B4C4D] hover:border-[#760604] shadow-xl flex flex-col justify-between transition-all duration-300 group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#760604] text-white flex items-center justify-center mb-4 shadow-md group-hover:scale-110 transition-transform">
                            <i data-lucide="user-check" class="w-5 h-5"></i>
                        </div>
                        <h4 class="font-bold text-base text-white">Pejabat &amp; Wilayah Kerja</h4>
                        <p class="text-xs text-slate-300 mt-1">Identitas resmi pejabat yang berwenang</p>
                        
                        <div class="mt-5 space-y-3 text-xs">
                            <div class="bg-[#1c1d1e] p-3 rounded-xl border border-[#4B4C4D]/60">
                                <span class="text-red-300 block text-[11px] mb-0.5 font-medium">Nama PPAT</span>
                                <span class="font-bold text-white text-sm">Iwan Kurniawan, SH., MH., M.Kn</span>
                            </div>
                            <div class="bg-[#1c1d1e] p-3 rounded-xl border border-[#4B4C4D]/60">
                                <span class="text-red-300 block text-[11px] mb-0.5 font-medium">Wilayah Kerja</span>
                                <span class="font-semibold text-white">Kabupaten Muara Enim</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Surat Keputusan (SK) Penunjukan -->
                <div class="bg-[#2b2c2d] rounded-2xl p-6 sm:p-7 border border-[#4B4C4D] hover:border-[#760604] shadow-xl flex flex-col justify-between transition-all duration-300 group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#760604] text-white flex items-center justify-center mb-4 shadow-md group-hover:scale-110 transition-transform">
                            <i data-lucide="award" class="w-5 h-5"></i>
                        </div>
                        <h4 class="font-bold text-base text-white">SK Penunjukan Menteri ATR/BPN</h4>
                        <p class="text-xs text-slate-300 mt-1">Dasar hukum dan surat keputusan pengangkatan</p>
                        
                        <div class="mt-5 space-y-3 text-xs">
                            <div class="bg-[#1c1d1e] p-3 rounded-xl border border-[#4B4C4D]/60">
                                <span class="text-red-300 block text-[11px] mb-0.5 font-medium">Nomor Surat Keputusan</span>
                                <span class="font-mono font-semibold text-white text-xs">37/SK-HR.03.04.PPAT/III/2023</span>
                            </div>
                            <div class="bg-[#1c1d1e] p-3 rounded-xl border border-[#4B4C4D]/60">
                                <span class="text-red-300 block text-[11px] mb-0.5 font-medium">Tanggal Penetapan SK</span>
                                <span class="font-semibold text-white">28 Maret 2023</span>
                            </div>
                            <div class="bg-[#1c1d1e] p-3 rounded-xl border border-[#4B4C4D]/60">
                                <span class="text-red-300 block text-[11px] mb-0.5 font-medium">Instansi Penerbit</span>
                                <span class="font-medium text-slate-200">Menteri Agraria dan Tata Ruang / Kepala BPN RI</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Alamat Kantor & Kontak -->
                <div class="bg-[#2b2c2d] rounded-2xl p-6 sm:p-7 border border-[#4B4C4D] hover:border-[#760604] shadow-xl md:col-span-2 lg:col-span-1 flex flex-col justify-between transition-all duration-300 group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#760604] text-white flex items-center justify-center mb-4 shadow-md group-hover:scale-110 transition-transform">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <h4 class="font-bold text-base text-white">Alamat &amp; Kontak Kantor</h4>
                        <p class="text-xs text-slate-300 mt-1">Lokasi kantor pelayanan dan kontak resmi</p>
                        
                        <div class="mt-5 space-y-3 text-xs">
                            <div class="bg-[#1c1d1e] p-3 rounded-xl border border-[#4B4C4D]/60">
                                <span class="text-red-300 block text-[11px] mb-0.5 font-medium">Alamat Lengkap</span>
                                <span class="font-medium text-white leading-relaxed block">
                                    Jl. Tembesu No. 2 RT.01/03 Pasar I Pelita Sari (Depan SMAN 1 Muara Enim), Kec. Muara Enim, Kab. Muara Enim, Kode Pos: 31311
                                </span>
                            </div>
                            <div class="grid grid-cols-1 gap-2">
                                <a href="mailto:Iwannotppat1@gmail.com" class="p-2.5 rounded-xl bg-[#1c1d1e] hover:bg-[#760604] border border-[#4B4C4D] hover:border-red-400 flex items-center gap-2 transition text-white group/mail">
                                    <i data-lucide="mail" class="w-4 h-4 text-red-400 group-hover/mail:text-white shrink-0"></i>
                                    <span class="font-medium text-white truncate">Iwannotppat1@gmail.com</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2b. Section Layanan Akta PPAT Muara Enim (SEO Keyword & Search Intent Anchor) -->
    <section class="py-16 sm:py-20 bg-[#171819] border-b border-[#2d2e2f]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-4 py-1.5 rounded-full bg-[#760604]/80 text-red-200 text-xs font-bold uppercase tracking-wider mb-3 border border-red-500/30">
                    Pelayanan Akta Tanah Resmi
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    Layanan PPAT Kabupaten Muara Enim
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 mt-2">
                    Kami melayani pembuatan berbagai akta autentik peralihan dan pembebanan hak atas tanah sesuai peraturan perundang-undangan Republik Indonesia.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-[#242526] p-6 rounded-2xl border border-[#3a3b3c] hover:border-red-500/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-[#760604] text-white flex items-center justify-center mb-4">
                        <i data-lucide="file-check-2" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-white text-base">Akta Jual Beli (AJB)</h3>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                        Pengurusan resmi peralihan hak atas tanah dan bangunan melalui jual beli yang sah dan didaftarkan ke Kantor Pertanahan (BPN) Muara Enim.
                    </p>
                </div>

                <div class="bg-[#242526] p-6 rounded-2xl border border-[#3a3b3c] hover:border-red-500/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-[#760604] text-white flex items-center justify-center mb-4">
                        <i data-lucide="gift" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-white text-base">Akta Hibah Tanah</h3>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                        Pemberian sukarela sebidang tanah atau bangunan dari pemberi hibah kepada penerima hibah dengan kekuatan hukum yang pasti.
                    </p>
                </div>

                <div class="bg-[#242526] p-6 rounded-2xl border border-[#3a3b3c] hover:border-red-500/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-[#760604] text-white flex items-center justify-center mb-4">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-white text-base">Pembagian Hak Bersama (APHB)</h3>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                        Pembuatan akta pembagian tanah warisan atau kepemilikan bersama kepada masing-masing ahli waris secara proporsional dan berkekuatan hukum.
                    </p>
                </div>

                <div class="bg-[#242526] p-6 rounded-2xl border border-[#3a3b3c] hover:border-red-500/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-[#760604] text-white flex items-center justify-center mb-4">
                        <i data-lucide="landmark" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-white text-base">Akta Pemberian Hak Tanggungan (APHT)</h3>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                        Pengikatan jaminan hak atas tanah untuk fasilitas kredit perbankan, Surat Kuasa Membebankan Hak Tanggungan (SKMHT), dan roya sertifikat.
                    </p>
                </div>

                <div class="bg-[#242526] p-6 rounded-2xl border border-[#3a3b3c] hover:border-red-500/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-[#760604] text-white flex items-center justify-center mb-4">
                        <i data-lucide="repeat" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-white text-base">Tukar Menukar &amp; Inbreng</h3>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                        Pembuatan akta tukar menukar hak tanah serta pemasukan tanah ke dalam perusahaan (inbreng modal perseroan).
                    </p>
                </div>

                <div class="bg-[#242526] p-6 rounded-2xl border border-[#3a3b3c] hover:border-red-500/50 transition">
                    <div class="w-10 h-10 rounded-xl bg-[#760604] text-white flex items-center justify-center mb-4">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-white text-base">Pengecekan &amp; Validasi Sertifikat</h3>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                        Pengecekan keaslian sertifikat ke BPN Muara Enim, validasi BPHTB Bapenda, serta verifikasi PPh Final sebelum transaksi dibuat.
                    </p>
                </div>
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('layanan.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#760604] hover:bg-[#8f0907] text-white text-xs sm:text-sm font-bold shadow-lg transition border border-red-500/40">
                    <i data-lucide="clipboard-list" class="w-4 h-4 text-red-200"></i>
                    <span>Lihat Rincian Persyaratan &amp; Prosedur Layanan</span>
                </a>
            </div>
        </div>
    </section>

    <!-- 3. Footer Resmi (Dominan Blood Red) -->
    <footer id="kontak" class="bg-[#760604] text-white border-t-2 border-red-900 shadow-2xl mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: Brand & Bio -->
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-ppat.jpg') }}" alt="Logo IPPAT" class="w-10 h-10 object-contain shrink-0 rounded-full shadow-md bg-white p-0.5">
                        <h4 class="font-extrabold text-white text-base">PPAT Iwan Kurniawan, SH., MH., M.Kn</h4>
                    </div>
                    <p class="text-xs text-red-100 leading-relaxed max-w-md">
                        Pejabat Pembuat Akta Tanah (PPAT) Kabupaten Muara Enim berdasarkan SK Menteri Agraria dan Tata Ruang / Kepala BPN RI No. 37/SK-HR.03.04.PPAT/III/2023. Melayani pembuatan akta tanah dengan standar pelayanan profesional dan tertib administrasi kearsipan.
                    </p>
                </div>

                <!-- Col 2: Navigasi Cepat -->
                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-3">Tautan Halaman</h5>
                    <ul class="space-y-2 text-xs text-red-100">
                        <li><a href="#beranda" class="hover:text-white hover:underline transition">Beranda Utama</a></li>
                        <li><a href="#profil" class="hover:text-white hover:underline transition">Profil &amp; SK Legalitas</a></li>
                        <li><a href="{{ route('layanan.index') }}" class="hover:text-white hover:underline transition font-bold text-white">Panduan Layanan &amp; Syarat</a></li>
                        <li><a href="{{ route('materi.index') }}" class="hover:text-white hover:underline transition">Materi &amp; Artikel</a></li>
                        <li><a href="#kontak" class="hover:text-white hover:underline transition">Kontak &amp; Pelayanan</a></li>
                    </ul>
                </div>

                <!-- Col 3: Kontak & Alamat -->
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
                    <span>Sistem Kearsipan SQLite</span>
                    <span>&bull;</span>
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
