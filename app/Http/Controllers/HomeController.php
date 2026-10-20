<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the public landing page.
     */
    public function index(): View
    {
        $stats = [
            'total' => Document::count(),
            'surat_masuk' => Document::where('type', 'Surat Masuk')->count(),
            'surat_keluar' => Document::where('type', 'Surat Keluar')->count(),
            'berkas' => Document::where('type', 'Berkas')->count(),
            'laporan_bpn' => Document::where('type', 'Laporan')->where('sub_type', 'Laporan BPN')->count(),
            'laporan_dispenda' => Document::where('type', 'Laporan')->where('sub_type', 'Laporan Dispenda')->count(),
        ];

        return view('welcome', compact('stats'));
    }
}
