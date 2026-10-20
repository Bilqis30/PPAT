<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard: Summary cards and recent activity.
     */
    public function admin(): View
    {
        $stats = [
            'total' => Document::count(),
            'surat_masuk' => Document::where('type', 'Surat Masuk')->count(),
            'surat_keluar' => Document::where('type', 'Surat Keluar')->count(),
            'berkas' => Document::where('type', 'Berkas')->count(),
            'laporan_bpn' => Document::where('type', 'Laporan')->where('sub_type', 'Laporan BPN')->count(),
            'laporan_dispenda' => Document::where('type', 'Laporan')->where('sub_type', 'Laporan Dispenda')->count(),
        ];

        $recentDocuments = Document::with('user')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentDocuments'));
    }

    /**
     * Pimpinan Dashboard: Executive summary & chart visualizations.
     */
    public function pimpinan(): View
    {
        $stats = [
            'total' => Document::count(),
            'surat_masuk' => Document::where('type', 'Surat Masuk')->count(),
            'surat_keluar' => Document::where('type', 'Surat Keluar')->count(),
            'berkas' => Document::where('type', 'Berkas')->count(),
            'laporan_bpn' => Document::where('type', 'Laporan')->where('sub_type', 'Laporan BPN')->count(),
            'laporan_dispenda' => Document::where('type', 'Laporan')->where('sub_type', 'Laporan Dispenda')->count(),
        ];

        // Chart Data 1: Category breakdown
        $categoryBreakdown = [
            'labels' => ['Surat Masuk', 'Surat Keluar', 'Berkas', 'Laporan BPN', 'Laporan Dispenda'],
            'data' => [
                $stats['surat_masuk'],
                $stats['surat_keluar'],
                $stats['berkas'],
                $stats['laporan_bpn'],
                $stats['laporan_dispenda'],
            ],
        ];

        // Chart Data 2: Monthly distribution (last 6 months)
        $months = collect(range(5, 0))->map(function ($i) {
            return now()->subMonths($i)->format('Y-m');
        });

        $monthlyData = $months->map(function ($month) {
            return Document::where('document_date', 'like', "{$month}%")->count();
        });

        $monthlyLabels = $months->map(function ($month) {
            return date('M Y', strtotime($month.'-01'));
        });

        $recentDocuments = Document::with('user')
            ->latest('document_date')
            ->take(5)
            ->get();

        return view('pimpinan.dashboard', compact(
            'stats',
            'categoryBreakdown',
            'monthlyLabels',
            'monthlyData',
            'recentDocuments'
        ));
    }
}
