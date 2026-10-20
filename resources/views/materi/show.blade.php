<!DOCTYPE html>
<html lang="id" class="scroll-smooth bg-[#121314] text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $materi->title }} - Kantor PPAT Iwan Kurniawan</title>

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
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo-ppat.jpg') }}" alt="Logo IPPAT" class="w-11 h-11 object-contain shrink-0 rounded-full shadow-md border border-[#760604]/50 group-hover:scale-105 transition bg-white p-0.5">
                <div class="leading-tight">
                    <h1 class="font-bold text-sm sm:text-base text-white tracking-tight">PPAT Iwan Kurniawan, SH., MH., M.Kn</h1>
                    <p class="text-xs text-slate-300 font-medium">Pejabat Pembuat Akta Tanah &bull; Kab. Muara Enim</p>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-slate-200">
                <a href="{{ route('home') }}" class="hover:text-red-400 transition">Beranda</a>
                <a href="{{ route('home') }}#profil" class="hover:text-red-400 transition">Profil Kantor</a>
                <a href="{{ route('layanan.index') }}" class="hover:text-red-400 transition">Panduan Layanan</a>
                <a href="{{ route('materi.index') }}" class="text-white font-bold border-b-2 border-red-500 pb-1">Materi</a>
                <a href="{{ route('home') }}#kontak" class="hover:text-red-400 transition">Kontak</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('materi.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#2b2c2d] hover:bg-[#3a3b3c] text-white text-xs font-semibold transition border border-[#4B4C4D]">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5 text-red-300"></i>
                    <span>Kembali ke Materi</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Article Container -->
    <main class="flex-1 py-10 sm:py-16 bg-[#121314]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-slate-500"></i>
                <a href="{{ route('materi.index') }}" class="hover:text-white transition">Materi</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-slate-500"></i>
                <span class="text-slate-200 font-medium truncate max-w-xs">{{ $materi->title }}</span>
            </nav>

            <article class="bg-[#1c1d1e] rounded-3xl border border-[#4B4C4D] shadow-2xl overflow-hidden">
                <!-- Cover Image -->
                <div class="relative w-full h-72 sm:h-96 bg-[#2b2c2d] overflow-hidden">
                    <img src="{{ $materi->cover_image_url }}" alt="{{ $materi->title }}" class="w-full h-full object-cover">
                </div>

                <!-- Header Meta -->
                <div class="p-6 sm:p-10 border-b border-[#2d2e2f]">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#760604] text-white border border-red-400/50">
                                {{ $materi->category ?? 'Artikel' }}
                            </span>
                            <div class="flex items-center gap-1.5 text-xs text-slate-300 font-medium">
                                <span>🗓️</span>
                                <span>{{ $materi->formatted_date }}</span>
                            </div>
                            <div class="flex items-center gap-1 text-xs text-slate-300">
                                <i data-lucide="user" class="w-3.5 h-3.5 text-red-400"></i>
                                <span>{{ $materi->author_name ?? 'PPAT Iwan Kurniawan' }}</span>
                            </div>
                        </div>

                        @auth
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.materi.edit', $materi) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#760604] hover:bg-[#8f0907] text-white text-xs font-semibold shadow-md transition border border-red-400/50">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5 text-red-200"></i>
                                    <span>Edit Tulisan Ini</span>
                                </a>
                            @endif
                        @endauth
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        {{ $materi->title }}
                    </h1>

                    @if($materi->excerpt)
                        <p class="mt-4 text-base sm:text-lg text-slate-200 leading-relaxed font-normal italic border-l-4 border-red-500 pl-4 bg-[#2b2c2d] py-3 rounded-r-xl">
                            {{ $materi->excerpt }}
                        </p>
                    @endif
                </div>

                <!-- Content Body -->
                <div class="p-6 sm:p-10 text-slate-200 leading-relaxed space-y-5 text-sm sm:text-base">
                    {!! nl2br(e($materi->content)) !!}
                </div>

                <!-- Footer of Article -->
                <div class="px-6 py-5 sm:px-10 bg-[#232425] border-t border-[#2d2e2f] flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-slate-300">
                        Dipublikasikan secara resmi oleh <strong class="text-white">Kantor PPAT Iwan Kurniawan, SH., MH., M.Kn</strong>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('materi.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#760604] hover:bg-[#8f0907] text-white text-xs font-semibold transition border border-red-400/40">
                            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                            <span>Daftar Materi</span>
                        </a>
                    </div>
                </div>
            </article>

            <!-- Related Articles -->
            @if(isset($recentMateris) && $recentMateris->count() > 0)
                <div class="mt-12">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <i data-lucide="sparkles" class="w-5 h-5 text-red-400"></i>
                        <span>Materi Lainnya</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                        @foreach($recentMateris as $recent)
                            <article class="bg-[#1c1d1e] rounded-2xl overflow-hidden border border-[#4B4C4D] hover:border-[#760604] hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                                <div class="w-full h-36 overflow-hidden bg-[#2b2c2d]">
                                    <img src="{{ $recent->cover_image_url }}" alt="{{ $recent->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                                <div class="p-4 flex-1 flex flex-col justify-between">
                                    <h4 class="text-white text-sm font-bold leading-snug group-hover:text-red-300 transition">
                                        <span class="text-red-400 font-extrabold">{{ $recent->category ?? 'Artikel' }} :</span>
                                        <span>{{ $recent->title }}</span>
                                    </h4>
                                    <div class="mt-3 pt-3 border-t border-[#2b2c2d] flex items-center justify-between text-xs">
                                        <span class="text-slate-400 font-medium">🗓️ {{ $recent->formatted_date }}</span>
                                        <a href="{{ route('materi.show', $recent->slug) }}" class="text-red-400 hover:text-red-300 font-bold">Baca &rarr;</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#760604] text-white border-t-2 border-red-900 mt-auto py-8 text-center text-xs text-red-200">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Kantor PPAT Iwan Kurniawan, SH., MH., M.Kn. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
