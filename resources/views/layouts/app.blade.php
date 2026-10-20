<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WebArsipp') - Manajemen Arsip Digital</title>

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN) & Dedicated Theme Configuration JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind-config.js') }}"></script>

    <!-- Dedicated Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Chart.js for Pimpinan Dashboard Visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('styles')
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50 flex">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity"></div>

    <!-- Sidebar Navigation -->
    <aside id="main-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static lg:inset-auto">
        <!-- Logo Header -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo-ppat.jpg') }}" alt="Logo IPPAT" class="w-10 h-10 object-contain shrink-0 rounded-full shadow-xs group-hover:scale-105 transition">
                <div>
                    <h1 class="font-bold text-sm leading-tight text-slate-900 tracking-tight">PPAT Iwan Kurniawan</h1>
                    <p class="text-[11px] text-slate-400 font-medium">Kab. Muara Enim</p>
                </div>
            </a>
            <button onclick="toggleSidebar()" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100" aria-label="Tutup Menu">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Role Badge & User Info -->
        <a href="{{ Auth::user()->isAdmin() ? route('admin.profile.edit') : route('pimpinan.profile.edit') }}" 
           title="Buka Pengaturan Profil & Keamanan"
           class="px-6 py-4 border-b border-slate-100 bg-slate-50/60 hover:bg-slate-100/80 transition-colors block group">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-semibold text-sm group-hover:scale-105 transition-transform">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-900 truncate group-hover:text-slate-700 transition">{{ Auth::user()->name ?? 'User' }}</p>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
                    </div>
                    
                </div>
            </div>
        </a>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6">
            @if(Auth::user()->isAdmin())
                <!-- Menu Admin -->
                <div>
                    <div class="px-3 mb-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                        Menu Pengelola Arsip
                    </div>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                            <span>Dashboard Admin</span>
                        </a>

                        <a href="{{ route('admin.documents.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.documents.index') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i data-lucide="folder-archive" class="w-4 h-4 {{ request()->routeIs('admin.documents.index') ? 'text-white' : 'text-slate-400' }}"></i>
                            <span>Arsip Dokumen</span>
                        </a>

                        <a href="{{ route('admin.drive.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.drive.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i data-lucide="hard-drive" class="w-4 h-4 {{ request()->routeIs('admin.drive.*') ? 'text-white' : 'text-slate-400' }}"></i>
                            <div class="flex items-center justify-between flex-1">
                                <span>Drive Berkas</span>
                                <span class="text-[9px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded bg-amber-100 text-amber-800">Folder</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.documents.create') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.documents.create') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i data-lucide="upload-cloud" class="w-4 h-4 {{ request()->routeIs('admin.documents.create') ? 'text-white' : 'text-slate-400' }}"></i>
                            <span>Upload Dokumen</span>
                        </a>

                        <a href="{{ route('admin.layanan.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.layanan.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i data-lucide="clipboard-list" class="w-4 h-4 {{ request()->routeIs('admin.layanan.*') ? 'text-white' : 'text-slate-400' }}"></i>
                            <span>Panduan &amp; Layanan</span>
                        </a>

                        <a href="{{ route('admin.materi.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.materi.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i data-lucide="book-open" class="w-4 h-4 {{ request()->routeIs('admin.materi.*') ? 'text-white' : 'text-slate-400' }}"></i>
                            <span>Materi &amp; Artikel</span>
                        </a>

                        <a href="{{ route('admin.profile.edit') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.profile.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i data-lucide="user-cog" class="w-4 h-4 {{ request()->routeIs('admin.profile.*') ? 'text-white' : 'text-slate-400' }}"></i>
                            <span>Profil & Keamanan</span>
                        </a>

                        <a href="{{ route('admin.settings.whatsapp') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i data-lucide="message-square" class="w-4 h-4 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-slate-400' }}"></i>
                            <span>Pengaturan WhatsApp</span>
                        </a>
                    </nav>
                </div>
            @else
                <!-- Menu Pimpinan -->
                <div>
                    <div class="px-3 mb-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                        Ruang Monitoring Eksekutif
                    </div>
                    <nav class="space-y-1">
                        <a href="{{ route('pimpinan.dashboard') }}" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('pimpinan.dashboard') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 {{ request()->routeIs('pimpinan.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                            <span>Dashboard Pimpinan</span>
                        </a>

                        <a href="{{ route('pimpinan.monitoring') }}" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('pimpinan.monitoring') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i data-lucide="scan-search" class="w-4 h-4 {{ request()->routeIs('pimpinan.monitoring') ? 'text-white' : 'text-slate-400' }}"></i>
                            <span>Monitoring Arsip</span>
                        </a>

                        <a href="{{ route('pimpinan.drive.index') }}" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('pimpinan.drive.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i data-lucide="hard-drive" class="w-4 h-4 {{ request()->routeIs('pimpinan.drive.*') ? 'text-white' : 'text-slate-400' }}"></i>
                            <span>Drive Berkas Proyek</span>
                        </a>

                        <a href="{{ route('pimpinan.profile.edit') }}" 
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('pimpinan.profile.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i data-lucide="user-cog" class="w-4 h-4 {{ request()->routeIs('pimpinan.profile.*') ? 'text-white' : 'text-slate-400' }}"></i>
                            <span>Profil & Keamanan</span>
                        </a>
                    </nav>

                    <div class="mt-6 mx-2 p-3 bg-amber-50/70 border border-amber-200/60 rounded-xl text-xs text-amber-800 flex items-start gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5"></i>
                        <span>Akses Anda adalah <strong>Read-Only Monitoring</strong>. Seluruh arsip disajikan transparan tanpa opsi manipulasi data.</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Logout & Footer -->
        <div class="p-4 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium text-rose-600 hover:bg-rose-50 transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Keluar dari Akun</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto">
        <!-- Topbar Header (Responsive) -->
        <header class="h-24 sm:h-20  bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 px-4 py-4 sm:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-500 hover:text-slate-800 rounded-lg hover:bg-slate-100 shrink-0" aria-label="Buka Menu Navigasi">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <div class="truncate">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 truncate">@yield('page_title', 'Dashboard')</h2>
                    <p class="text-[11px] sm:text-xs text-slate-400 hidden sm:block truncate">@yield('page_subtitle', 'Sistem Manajemen dan Kearsipan Dokumen')</p>
                </div>
            </div>

            <!-- Header Right Stats/Actions -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                

                <!-- Lonceng Notifikasi Terpadu (Khusus Pimpinan & Petugas) -->
                <div class="relative" id="notification-dropdown-container">
                    <button onclick="toggleNotificationDropdown()" 
                            id="notification-bell-btn"
                            type="button"
                            class="relative p-2 rounded-xl text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-100 transition border border-slate-200 shadow-sm cursor-pointer" 
                            title="Pemberitahuan Berkas & Arsip"
                            aria-label="Notifikasi Pimpinan">
                        <i data-lucide="bell" class="w-4 h-4 text-slate-700"></i>
                        @if(isset($topbarNotificationCount) && $topbarNotificationCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-4 min-w-[16px] px-1 items-center justify-center rounded-full bg-rose-600 text-[10px] font-bold text-white shadow ring-2 ring-white">
                                {{ $topbarNotificationCount > 9 ? '9+' : $topbarNotificationCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Panel Notifikasi -->
                    <div id="notification-dropdown-menu" 
                         class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl border border-slate-200 shadow-2xl z-50 overflow-hidden divide-y divide-slate-100">
                        <!-- Header Dropdown -->
                        <div class="p-3.5 sm:p-4 bg-slate-50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center">
                                    <i data-lucide="bell-ring" class="w-3.5 h-3.5 text-white"></i>
                                </div>
                                <div>
                                    <span class="font-bold text-xs sm:text-sm text-slate-900 block leading-tight">Pemberitahuan Pimpinan</span>
                                    <span class="text-[10px] text-slate-400">Arsip &amp; Dokumen Masuk</span>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                {{ $topbarNotificationCount ?? 0 }} Baru
                            </span>
                        </div>

                        <!-- Notification Items List -->
                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 text-xs">
                            @forelse($topbarNotifications ?? [] as $notif)
                                <div class="p-3 sm:p-3.5 hover:bg-slate-50 flex items-start gap-3 transition group">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-slate-900 group-hover:text-white transition">
                                        @if($notif->type === 'Surat Masuk')
                                            <i data-lucide="inbox" class="w-4 h-4 text-sky-600 group-hover:text-white"></i>
                                        @elseif($notif->type === 'Surat Keluar')
                                            <i data-lucide="send" class="w-4 h-4 text-indigo-600 group-hover:text-white"></i>
                                        @elseif($notif->type === 'Berkas')
                                            <i data-lucide="folder" class="w-4 h-4 text-amber-600 group-hover:text-white"></i>
                                        @else
                                            <i data-lucide="file-check-2" class="w-4 h-4 text-teal-600 group-hover:text-white"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1 mb-0.5">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                {{ $notif->sub_type ?? $notif->type }}
                                            </span>
                                            <span class="text-[10px] text-slate-400">
                                                {{ $notif->created_at ? $notif->created_at->diffForHumans() : $notif->document_date?->format('d M Y') }}
                                            </span>
                                        </div>
                                        <a href="{{ route('documents.show', $notif) }}" class="font-semibold text-slate-900 hover:text-slate-700 truncate block">
                                            {{ $notif->title }}
                                        </a>
                                        <div class="flex items-center justify-between mt-1 text-[11px] text-slate-400">
                                            <span class="font-mono text-[10px] truncate">{{ $notif->document_number ?? 'Tanpa Nomor' }}</span>
                                            <button type="button" 
                                                    onclick="openPdfModal('{{ route('documents.preview', $notif) }}', '{{ addslashes($notif->title) }}', '{{ route('documents.download', $notif) }}')"
                                                    class="text-sky-600 hover:text-sky-800 font-medium inline-flex items-center gap-1 cursor-pointer">
                                                <i data-lucide="eye" class="w-3 h-3"></i>
                                                <span>Pratinjau</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-slate-400 text-xs">
                                    <i data-lucide="check-circle" class="w-6 h-6 mx-auto mb-2 text-slate-300"></i>
                                    <span>Belum ada dokumen atau notifikasi baru.</span>
                                </div>
                            @endforelse
                        </div>

                        <!-- Footer Dropdown -->
                        <div class="p-2.5 bg-slate-50 text-center">
                            <a href="{{ Auth::user()->isAdmin() ? route('admin.documents.index') : route('pimpinan.monitoring') }}" 
                               class="text-xs font-semibold text-slate-700 hover:text-slate-900 flex items-center justify-center gap-1.5 py-1">
                                <span>Lihat Seluruh Berkas Monitoring</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ Auth::user()->isAdmin() ? route('admin.profile.edit') : route('pimpinan.profile.edit') }}" 
                   title="Kelola Profil & Kata Sandi"
                   class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-medium border {{ request()->routeIs('*.profile.*') ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }} transition shadow-sm">
                    <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    <span class="hidden sm:inline">Profil</span>
                </a>
                @yield('topbar_actions')
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 p-4 sm:p-6 w-full max-w-[1400px] mx-auto">
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="auto-dismiss-alert mb-5 p-3.5 sm:p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                        </div>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 ml-2" aria-label="Tutup Notifikasi">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            @if(session('info'))
                <div class="auto-dismiss-alert mb-5 p-3.5 sm:p-4 rounded-xl bg-sky-50 border border-sky-200 text-sky-800 text-xs sm:text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center shrink-0">
                            <i data-lucide="info" class="w-4 h-4"></i>
                        </div>
                        <span class="font-medium">{{ session('info') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-sky-500 hover:text-sky-700 ml-2" aria-label="Tutup Notifikasi">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-3.5 sm:p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="font-semibold mb-1">Terdapat beberapa kesalahan pengisian:</p>
                            <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Global Inline PDF Preview Modal -->
    <div id="pdf-preview-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-2 sm:p-6 transition-opacity">
        <div class="modal-card modal-animate-in bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-5xl h-[88vh] flex flex-col overflow-hidden">
            <!-- Modal Header -->
            <div class="px-4 sm:px-6 py-3.5 border-b border-slate-200 flex items-center justify-between bg-slate-50 shrink-0">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <div class="truncate">
                        <h3 id="pdf-modal-title" class="font-semibold text-slate-900 text-xs sm:text-sm truncate">Pratinjau Dokumen</h3>
                        <p class="text-[11px] text-slate-400 hidden sm:block">Berkas Arsip Resmi</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                    <a id="pdf-modal-download-btn" href="#" download class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition">
                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Unduh</span>
                    </a>
                    <a id="pdf-modal-newtab-btn" href="#" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Tab Baru</span>
                    </a>
                    <button onclick="closePdfModal()" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-200/60 transition" aria-label="Tutup Pratinjau">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            <!-- Modal Body (Iframe) -->
            <div class="flex-1 bg-slate-100 relative">
                <iframe id="pdf-iframe" src="" class="w-full h-full border-0"></iframe>
            </div>
        </div>
    </div>

    <!-- Global Delete Confirmation Modal (Center of Screen) -->
    <div id="delete-confirm-modal" onclick="closeDeleteModal()" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4 transition-all duration-200">
        <!-- Modal Card -->
        <div class="modal-card modal-animate-in bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-md overflow-hidden relative" onclick="event.stopPropagation()">
            <!-- Close Button in Top Right Corner -->
            <button type="button" onclick="closeDeleteModal()" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition" aria-label="Tutup Dialog">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>

            <div class="p-6 sm:p-7 text-center">
                <!-- Icon Alert Highlight -->
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                        <i data-lucide="trash-2" class="w-5 h-5 text-rose-600"></i>
                    </div>
                </div>

                <!-- Title & Subtitle -->
                <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1.5 tracking-tight">Hapus Arsip Dokumen?</h3>
                <p class="text-xs sm:text-sm text-slate-500 mb-4">
                    Apakah Anda yakin ingin menghapus arsip ini?
                </p>

                <!-- Document Title Highlight Box -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 mb-4 text-left flex items-start gap-2.5">
                    <i data-lucide="file-text" class="w-4 h-4 text-slate-400 shrink-0 mt-0.5"></i>
                    <div class="min-w-0 flex-1">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-0.5">Judul Dokumen</span>
                        <p id="delete-confirm-doc-title" class="text-xs font-semibold text-slate-800 break-words line-clamp-2">
                            Dokumen Arsip
                        </p>
                    </div>
                </div>

                <!-- Danger Warning Note -->
                <div class="p-3 rounded-xl bg-rose-50/70 border border-rose-200/70 text-rose-700 text-xs text-left mb-6 flex items-start gap-2">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 shrink-0 mt-0.5"></i>
                    <span>Tindakan ini akan <strong>menghapus data record</strong> serta berkas fisik PDF di server secara permanen. Tindakan tidak dapat dibatalkan.</span>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold transition active:scale-95 shadow-sm">
                        Batal
                    </button>
                    <form id="delete-confirm-form" method="POST" action="" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs sm:text-sm font-semibold transition shadow-sm flex items-center justify-center gap-1.5">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            <span>Ya, Hapus</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Application JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
