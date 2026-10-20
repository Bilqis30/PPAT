@extends('layouts.app')

@section('title', 'Profil Akun & Keamanan')
@section('page_title', 'Profil & Keamanan Akun')
@section('page_subtitle', 'Kelola informasi identitas akun dan perbarui kata sandi secara aman')


@section('content')
<div class="max-w-5xl mx-auto space-y-6 sm:space-y-8">
    
    <!-- 1. Header Profile Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white rounded-2xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
        <!-- Background Pattern Decor -->
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-4 sm:gap-5">
                <!-- Avatar Initial -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/10 border-2 border-white/20 text-white flex items-center justify-center font-bold text-2xl sm:text-3xl shadow-inner backdrop-blur-sm shrink-0">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-white">{{ $user->name }}</h2>
                        @if($user->isAdmin())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                <i data-lucide="shield-check" class="w-3.5 h-3.5 mr-1 inline"></i> Administrator Arsip
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-500/20 text-sky-300 border border-sky-500/30">
                                <i data-lucide="eye" class="w-3.5 h-3.5 mr-1 inline"></i> Pimpinan (Read-Only)
                            </span>
                        @endif
                    </div>
                    <p class="text-slate-300 text-xs sm:text-sm flex items-center gap-2">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>{{ $user->email }}</span>
                    </p>
                </div>
            </div>

            <!-- Account Meta Pills -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-3 bg-white/5 p-3 rounded-xl border border-white/10 backdrop-blur-sm text-xs text-slate-300">
                <div class="flex items-center gap-1.5 px-2.5 py-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-medium">Status Akun: <strong>Aktif</strong></span>
                </div>
                <div class="h-4 w-px bg-white/20 hidden sm:block"></div>
                <div class="flex items-center gap-1.5 px-2.5 py-1">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span>Terdaftar: <strong>{{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : 'N/A' }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Dual Form Grid: Update Profile & Change Password -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
        
        <!-- Left Column: Informasi Akun (5 cols on lg) -->
        <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
            <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                        <i data-lucide="user-check" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Informasi Profil</h3>
                        <p class="text-xs text-slate-400">Perbarui nama pengguna dan alamat email Anda</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ $updateRoute }}" class="p-5 sm:p-6 space-y-5 flex-1 flex flex-col justify-between">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border @error('name') border-rose-300 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition"
                                   placeholder="Masukkan nama lengkap">
                        </div>
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Alamat Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border @error('email') border-rose-300 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition"
                                   placeholder="nama@arsip.com">
                        </div>
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Role Hak Akses (Read-Only) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Peran Hak Akses (Otoritas Sistem)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </div>
                            <input type="text" disabled 
                                   value="{{ $user->isAdmin() ? 'Administrator Arsip (Hak Penuh Pengelolaan Arsip)' : 'Pimpinan Kantor (Hak Monitoring & Audit Arsip)' }}" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-600 cursor-not-allowed select-none">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5">
                            Peran akun dikunci untuk menjaga integritas keamanan dan pembagian kewenangan sistem.
                        </p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-6 flex justify-end">
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 text-white text-xs sm:text-sm font-semibold hover:bg-slate-800 active:scale-95 transition shadow-sm">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Simpan Perubahan Profil</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Column: Ganti Password (6 cols on lg) -->
        <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
            <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0">
                        <i data-lucide="key-round" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Ganti Kata Sandi</h3>
                        <p class="text-xs text-slate-400">Amankan akun Anda dengan password yang kuat</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ $passwordRoute }}" class="p-5 sm:p-6 space-y-5 flex-1 flex flex-col justify-between">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <!-- Password Saat Ini -->
                    <div>
                        <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Password Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </div>
                            <input type="password" id="current_password" name="current_password" required
                                   class="w-full pl-10 pr-11 py-2.5 bg-white border @error('current_password') border-rose-300 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition"
                                   placeholder="Masukkan password saat ini">
                            <button type="button" onclick="togglePasswordVisibility('current_password', this)" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition"
                                    aria-label="Tampilkan / Sembunyikan Password">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Password Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="key" class="w-4 h-4"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                   class="w-full pl-10 pr-11 py-2.5 bg-white border @error('password') border-rose-300 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition"
                                   placeholder="Minimal 8 karakter">
                            <button type="button" onclick="togglePasswordVisibility('password', this)" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition"
                                    aria-label="Tampilkan / Sembunyikan Password">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Konfirmasi Password Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="check-check" class="w-4 h-4"></i>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="w-full pl-10 pr-11 py-2.5 bg-white border @error('password_confirmation') border-rose-300 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition"
                                   placeholder="Ulangi password baru">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition"
                                    aria-label="Tampilkan / Sembunyikan Password">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Security Tips Banner -->
                    <div class="p-3 bg-amber-50/70 border border-amber-200/60 rounded-xl text-xs text-amber-900 space-y-1">
                        <div class="font-semibold flex items-center gap-1.5 text-amber-800">
                            <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-amber-600 shrink-0"></i>
                            <span>Ketentuan Keamanan Sandi:</span>
                        </div>
                        <ul class="list-disc list-inside text-[11px] text-amber-700 pl-1 space-y-0.5">
                            <li>Minimal 8 karakter</li>
                            <li>Gunakan gabungan huruf besar, huruf kecil, dan angka</li>
                            <li>Hindari menggunakan kata sandi yang mudah ditebak</li>
                        </ul>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-6 flex justify-end">
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs sm:text-sm font-semibold active:scale-95 transition shadow-sm">
                        <i data-lucide="key-round" class="w-4 h-4"></i>
                        <span>Perbarui Kata Sandi</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, buttonEl) {
        const input = document.getElementById(inputId);
        if (!input) return;

        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        // Update icon visually
        const icon = buttonEl.querySelector('i');
        if (icon) {
            icon.setAttribute('data-lucide', isPassword ? 'eye-off' : 'eye');
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    }
</script>
@endpush
