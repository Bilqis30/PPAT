<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Masuk Internal (Admin & Pimpinan) - WebArsipp</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN) & Theme Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind-config.js') }}"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen flex flex-col justify-center items-center p-4 bg-slate-50 relative overflow-y-auto selection:bg-slate-900 selection:text-white">
    <!-- Subtle Background Ambient Decor -->
    <div class="fixed -top-40 -right-40 w-80 sm:w-96 h-80 sm:h-96 bg-slate-200/50 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed -bottom-40 -left-40 w-80 sm:w-96 h-80 sm:h-96 bg-slate-200/50 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-md relative z-10 my-auto">
        <!-- Logo & Header (Compact) -->
        <div class="text-center mb-4 sm:mb-5">
            <img src="{{ asset('images/logo-ppat.jpg') }}" alt="Logo IPPAT" class="inline-block w-14 h-14 object-contain rounded-full shadow-xs mb-2">
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Portal Masuk Internal</h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">PPAT Iwan Kurniawan, SH., MH., M.Kn &bull; Kab. Muara Enim</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:p-6">
            <div class="mb-4 pb-3 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-[10px] font-semibold text-slate-700">
                        <i data-lucide="shield-check" class="w-3 h-3 text-slate-800"></i>
                        <span>Admin &amp; Pimpinan</span>
                    </span>
                    <h2 class="text-sm font-bold text-slate-900 mt-1">Otentikasi Pengguna</h2>
                </div>
                <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1 transition" title="Kembali ke Web Publik">
                    <i data-lucide="globe" class="w-3.5 h-3.5"></i>
                    <span>Web Publik</span>
                </a>
            </div>


            <!-- Error Alerts -->
            @if($errors->any())
                <div class="mb-3.5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-3.5 p-3 rounded-xl bg-sky-50 border border-sky-200 text-sky-700 text-xs flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 shrink-0"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-3.5">
                @csrf
                <div>
                    <label for="email" class="block text-[11px] font-semibold text-slate-700 mb-1 uppercase tracking-wider">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="nama@arsip.com"
                               class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-[11px] font-semibold text-slate-700 mb-1 uppercase tracking-wider">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                               placeholder="Masukkan kata sandi"
                               class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-0.5">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-slate-300 text-slate-900 focus:ring-0">
                        <span class="text-[11px] text-slate-600">Ingat saya</span>
                    </label>
                    <span class="text-[10px] text-slate-400">Role terdeteksi otomatis</span>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 active:scale-[0.99] text-white rounded-xl text-xs sm:text-sm font-semibold transition shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Masuk ke Sistem</span>
                </button>
            </form>
        </div>

        <div class="mt-4 text-center space-y-1.5">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Website Informasi</span>
            </a>
            <p class="text-center text-[10px] text-slate-400">
                &copy; {{ date('Y') }} WebArsipp &bull; Kantor PPAT Iwan Kurniawan, SH., MH., M.Kn
            </p>
        </div>
    </div>

    <!-- Separated Login Script -->
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
