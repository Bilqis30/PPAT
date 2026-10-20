@extends('layouts.app')

@section('title', 'Manajemen Panduan & Layanan')
@section('page_title', 'Panduan & Layanan PPAT')
@section('page_subtitle', 'Kelola informasi akta, prosedur langkah, persyaratan berkas, dan estimasi waktu')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Stats Bar -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-white rounded-xl border border-slate-200 shadow-xs text-xs">
                <span class="text-slate-400">Total Layanan:</span>
                <strong class="text-slate-900 ml-1">{{ $services->total() }}</strong>
            </div>
            <a href="{{ route('layanan.index') }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition">
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                <span>Lihat Halaman Publik</span>
            </a>
        </div>

        <!-- Add Button -->
        <a href="{{ route('admin.layanan.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-semibold transition shadow-xs">
            <i data-lucide="plus-circle" class="w-4 h-4 text-emerald-400"></i>
            <span>Tambah Layanan Baru</span>
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

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <form action="{{ route('admin.layanan.index') }}" method="GET" class="w-full sm:w-96 relative">
            <input type="text" 
                   name="q" 
                   value="{{ request('q') }}" 
                   placeholder="Cari judul layanan atau badge..." 
                   class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
        </form>

        @if(request('q'))
            <a href="{{ route('admin.layanan.index') }}" class="text-xs text-rose-600 hover:underline flex items-center gap-1">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                <span>Reset Pencarian</span>
            </a>
        @endif
    </div>

    <!-- Table of Services -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-bold">
                    <tr>
                        <th class="px-6 py-4">Urutan &amp; Ikon</th>
                        <th class="px-6 py-4">Nama Layanan &amp; Deskripsi</th>
                        <th class="px-6 py-4">Badge Kategori</th>
                        <th class="px-6 py-4">Estimasi Waktu</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($services as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Order & Icon -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-md bg-slate-100 text-slate-600 text-xs font-bold flex items-center justify-center">
                                        {{ $item->order_num }}
                                    </span>
                                    <div class="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-xs">
                                        <i data-lucide="{{ $item->icon ?: 'clipboard-list' }}" class="w-4 h-4 text-emerald-400"></i>
                                    </div>
                                </div>
                            </td>

                            <!-- Title & Desc -->
                            <td class="px-6 py-4 max-w-sm">
                                <span class="font-bold text-slate-900 text-sm block">
                                    {{ $item->title }}
                                </span>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                                    {{ $item->description }}
                                </p>
                                <div class="mt-2 flex items-center gap-3 text-[11px] text-slate-400">
                                    <span>• {{ count($item->steps ?? []) }} Langkah Prosedur</span>
                                    <span>• {{ count($item->requirements_penjual ?? []) }} Syarat Penjual</span>
                                    <span>• {{ count($item->requirements_pembeli ?? []) }} Syarat Pembeli</span>
                                </div>
                            </td>

                            <!-- Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->badge)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $item->badge }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>

                            <!-- Estimated Time -->
                            <td class="px-6 py-4 text-xs text-slate-600">
                                {{ $item->estimated_time ?? '-' }}
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        <span>Nonaktif</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.layanan.edit', $item) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition shadow-2xs">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-emerald-400"></i>
                                        <span>Edit</span>
                                    </a>
                                    <a href="{{ route('layanan.index') }}#{{ $item->slug }}" target="_blank" 
                                       title="Buka di Website Publik" 
                                       class="p-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.layanan.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan & panduan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Layanan" 
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
                                    <i data-lucide="clipboard-x" class="w-10 h-10 text-slate-300 mb-2"></i>
                                    <p class="text-sm font-medium">Belum ada layanan yang ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($services->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $services->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
