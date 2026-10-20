@extends('layouts.app')

@section('title', 'Pengaturan WhatsApp Gateway')
@section('page_title', 'Pengaturan WhatsApp Gateway')
@section('page_subtitle', 'Kelola konfigurasi API gateway, nomor tujuan, serta jadwal & waktu pengiriman notifikasi pengingat acara Surat Masuk')


@section('content')
<div class="max-w-4xl mx-auto space-y-6 sm:space-y-8">

    <!-- 1. Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white rounded-2xl p-6 sm:p-8 shadow-sm relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 flex items-center justify-center shrink-0">
                    <i data-lucide="message-square" class="w-7 h-7 sm:w-8 sm:h-8"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-white">WhatsApp Gateway</h2>
                        @if($settings['wa_is_active'])
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span> Service Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-700 text-slate-300 border border-slate-600">
                                <span class="w-2 h-2 rounded-full bg-slate-400 mr-1.5"></span> Service Nonaktif
                            </span>
                        @endif
                    </div>
                    <p class="text-slate-300 text-xs sm:text-sm">
                        Notifikasi pengingat acara dikirim <strong>H-{{ $settings['wa_reminder_days'] }}</strong> setiap pukul <strong>{{ $settings['wa_reminder_time'] }} WIB</strong> dari Surat Masuk
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Form Setting Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                    <i data-lucide="sliders" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">Konfigurasi Gateway & Penjadwalan</h3>
                    <p class="text-xs text-slate-400">Atur kredensial API, nomor penerima, waktu pengiriman, dan toggle aktif/nonaktif</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.whatsapp.update') }}" class="p-5 sm:p-7 space-y-6">
            @csrf
            @method('PUT')

            <!-- Toggle Switch Status Aktif / Nonaktif -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-700 flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                        <i data-lucide="power" class="w-5 h-5 {{ $settings['wa_is_active'] ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    </div>
                    <div>
                        <label for="wa_is_active" class="text-sm font-bold text-slate-900 block cursor-pointer">
                            Aktifkan Notifikasi WhatsApp Otomatis
                        </label>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Jika dinonaktifkan, pengiriman notifikasi pengingat WA harian akan dilewati secara aman oleh sistem.
                        </p>
                    </div>
                </div>

                <!-- Custom Toggle Switch -->
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                    <input type="checkbox" id="wa_is_active" name="wa_is_active" value="1" 
                           class="sr-only peer" {{ $settings['wa_is_active'] ? 'checked' : '' }}>
                    <div class="w-12 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                </label>
            </div>

            <!-- Grid 2 Kolom: Penjadwalan H-X & Jam Pengiriman -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-4 rounded-xl bg-slate-50/70 border border-slate-200/70">
                <!-- Field: Hari Pengingat (H-X) -->
                <div>
                    <label for="wa_reminder_days" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Waktu Pengingat (H- Berapa Hari)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <input type="number" id="wa_reminder_days" name="wa_reminder_days" min="0" max="30" required
                               value="{{ old('wa_reminder_days', $settings['wa_reminder_days']) }}"
                               class="w-full pl-10 pr-4 py-2.5 bg-white border @error('wa_reminder_days') border-rose-300 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition"
                               placeholder="2">
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1.5">
                        Tentukan berapa hari sebelum acara pesan dikirim (contoh: <strong>2</strong> untuk H-2, <strong>1</strong> untuk H-1, <strong>0</strong> untuk hari H).
                    </p>
                    @error('wa_reminder_days')
                        <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Field: Jam Pengiriman (HH:MM) -->
                <div>
                    <label for="wa_reminder_time" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Jam Pengiriman Pesan (WIB)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </div>
                        <input type="time" id="wa_reminder_time" name="wa_reminder_time" required
                               value="{{ old('wa_reminder_time', $settings['wa_reminder_time']) }}"
                               class="w-full pl-10 pr-4 py-2.5 bg-white border @error('wa_reminder_time') border-rose-300 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1.5">
                        Jam pengiriman otomatis harian oleh Laravel Task Scheduler (contoh: <strong>07:00</strong> pagi).
                    </p>
                    @error('wa_reminder_time')
                        <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Field: Gateway URL -->
            <div>
                <label for="wa_gateway_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    URL API WhatsApp Gateway
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="link" class="w-4 h-4"></i>
                    </div>
                    <input type="url" id="wa_gateway_url" name="wa_gateway_url" 
                           value="{{ old('wa_gateway_url', $settings['wa_gateway_url']) }}"
                           class="w-full pl-10 pr-4 py-2.5 bg-white border @error('wa_gateway_url') border-rose-300 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition"
                           placeholder="https://api.fonnte.com/send">
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5">
                    Endpoint API pengiriman pesan (misal: Fonnte <code>https://api.fonnte.com/send</code> atau Wablas API).
                </p>
                @error('wa_gateway_url')
                    <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Field: Token API Gateway -->
            <div>
                <label for="wa_gateway_token" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Token API Gateway (Authorization Key)
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="key" class="w-4 h-4"></i>
                    </div>
                    <input type="password" id="wa_gateway_token" name="wa_gateway_token" 
                           value="{{ old('wa_gateway_token', $settings['wa_gateway_token']) }}"
                           class="w-full pl-10 pr-11 py-2.5 bg-white border @error('wa_gateway_token') border-rose-300 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition"
                           placeholder="Masukkan Token / API Key WhatsApp Provider">
                    <button type="button" onclick="toggleTokenVisibility('wa_gateway_token', this)" 
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition"
                            aria-label="Tampilkan / Sembunyikan Token">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5">
                    Token otorisasi API dari provider layanan WhatsApp Gateway Anda.
                </p>
                @error('wa_gateway_token')
                    <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Field: Nomor HP Penerima -->
            <div>
                <label for="wa_recipient_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Nomor HP Penerima Notifikasi
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                    </div>
                    <input type="text" id="wa_recipient_number" name="wa_recipient_number" 
                           value="{{ old('wa_recipient_number', $settings['wa_recipient_number']) }}"
                           class="w-full pl-10 pr-4 py-2.5 bg-white border @error('wa_recipient_number') border-rose-300 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition"
                           placeholder="Contoh: 081234567890 atau 6281234567890">
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5">
                    Nomor WhatsApp tujuan yang akan menerima pesan pengingat agenda acara Surat Masuk.
                </p>
                @error('wa_recipient_number')
                    <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Info Guide Box -->
            <div class="p-4 rounded-xl bg-sky-50/70 border border-sky-200/60 text-xs text-sky-900 space-y-2">
                <div class="font-bold flex items-center gap-2 text-sky-800">
                    <i data-lucide="info" class="w-4 h-4 text-sky-600 shrink-0"></i>
                    <span>Informasi Eksekusi Scheduler & Pengujian:</span>
                </div>
                <ul class="list-disc list-inside text-sky-800/90 space-y-1 pl-1 text-[11px]">
                    <li>Perintah pengiriman berjalan otomatis setiap hari pukul <strong>{{ $settings['wa_reminder_time'] }} WIB</strong> via Laravel Task Scheduler.</li>
                    <li>Pengingat akan mengecek dokumen Surat Masuk dengan tanggal acara persis <strong>H-{{ $settings['wa_reminder_days'] }}</strong>.</li>
                   
                </ul>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-slate-900 text-white text-xs sm:text-sm font-semibold hover:bg-slate-800 active:scale-95 transition shadow-sm">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function toggleTokenVisibility(inputId, buttonEl) {
        const input = document.getElementById(inputId);
        if (!input) return;

        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

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
