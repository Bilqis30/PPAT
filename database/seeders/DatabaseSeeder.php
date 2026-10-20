<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SettingSeeder::class);
        $this->call(MateriSeeder::class);

        // 1. Create or Update Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@arsip.com'],
            [
                'name' => 'Administrator Arsip',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Create or Update Pimpinan User
        $pimpinan = User::firstOrCreate(
            ['email' => 'pimpinan@arsip.com'],
            [
                'name' => 'Kepala / Pimpinan',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
            ]
        );

        // 3. Ensure sample PDF file in private storage (storage/app/private/documents)
        if (! Storage::disk('local')->exists('documents')) {
            Storage::disk('local')->makeDirectory('documents');
        }
        if (! Storage::disk('public')->exists('documents')) {
            Storage::disk('public')->makeDirectory('documents');
        }

        $samplePdfPath = 'documents/dokumen_sampel.pdf';
        $minimalPdf = "%PDF-1.4\n1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj\n4 0 obj << /Length 58 >> stream\nBT /F1 20 Tf 50 720 Td (Sistem Manajemen Arsip Digital - WebArsipp) Tj ET\nendstream endobj\n5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\nxref\n0 6\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000244 00000 n \n0000000353 00000 n \ntrailer << /Size 6 /Root 1 0 R >>\nstartxref\n430\n%%EOF";

        if (! Storage::disk('local')->exists($samplePdfPath)) {
            Storage::disk('local')->put($samplePdfPath, $minimalPdf);
        }
        if (! Storage::disk('public')->exists($samplePdfPath)) {
            Storage::disk('public')->put($samplePdfPath, $minimalPdf);
        }

        $fileSize = Storage::disk('local')->exists($samplePdfPath) ? Storage::disk('local')->size($samplePdfPath) : 1024;

        // 4. Sample Documents across all categories
        $samples = [
            [
                'document_number' => '005/SM/BPN/IV/2026',
                'document_date' => now()->subDays(1)->format('Y-m-d'),
                'title' => 'Permohonan Validasi Batas Tanah Wilayah Timur',
                'type' => 'Surat Masuk',
                'sub_type' => null,
                'description' => 'Surat masuk dari instansi terkait perihal koordinasi validasi tapal batas wilayah timur.',
                'file_path' => $samplePdfPath,
                'file_name' => 'Surat_Masuk_Validasi_Tanah.pdf',
                'file_size' => $fileSize,
                'user_id' => $admin->id,
            ],
            [
                'document_number' => '012/SK/INTERNAL/IV/2026',
                'document_date' => now()->subDays(2)->format('Y-m-d'),
                'title' => 'Pemberitahuan Rekonsiliasi Pajak Daerah Semester I',
                'type' => 'Surat Keluar',
                'sub_type' => null,
                'description' => 'Surat keluar instruksi pelaporan pajak daerah triwulanan.',
                'file_path' => $samplePdfPath,
                'file_name' => 'Surat_Keluar_Rekonsiliasi_Pajak.pdf',
                'file_size' => $fileSize,
                'user_id' => $admin->id,
            ],
            [
                'document_number' => 'BKS-2026-089',
                'document_date' => now()->subDays(3)->format('Y-m-d'),
                'title' => 'Berkas Arsip Kepemilikan Lahan Aset Pemda Blok C',
                'type' => 'Berkas',
                'sub_type' => null,
                'description' => 'Dokumen arsip sertifikat dan berkas pendukung aset tetap pemerintah daerah.',
                'file_path' => $samplePdfPath,
                'file_name' => 'Berkas_Aset_Lahan_Pemda.pdf',
                'file_size' => $fileSize,
                'user_id' => $admin->id,
            ],
            [
                'document_number' => 'LAP/BPN-W4/2026/03',
                'document_date' => now()->subDays(4)->format('Y-m-d'),
                'title' => 'Laporan Rekapitulasi Sertifikasi Tanah Kantor BPN',
                'type' => 'Laporan',
                'sub_type' => 'Laporan BPN',
                'description' => 'Laporan resmi BPN terkait kemajuan pendaftaran tanah sistematis lengkap (PTSL).',
                'file_path' => $samplePdfPath,
                'file_name' => 'Laporan_BPN_PTSL_Q1_2026.pdf',
                'file_size' => $fileSize,
                'user_id' => $admin->id,
            ],
            [
                'document_number' => 'DISPENDA/PAD/2026/041',
                'document_date' => now()->subDays(5)->format('Y-m-d'),
                'title' => 'Laporan Realisasi Pendapatan Asli Daerah (PAD) Dispenda',
                'type' => 'Laporan',
                'sub_type' => 'Laporan Dispenda',
                'description' => 'Laporan realisasi penerimaan pajak dan retribusi daerah dinas pendapatan daerah.',
                'file_path' => $samplePdfPath,
                'file_name' => 'Laporan_Realisasi_PAD_Dispenda.pdf',
                'file_size' => $fileSize,
                'user_id' => $admin->id,
            ],
            [
                'document_number' => '018/SM/PROV/IV/2026',
                'document_date' => now()->subDays(6)->format('Y-m-d'),
                'title' => 'Undangan Rapat Koordinasi Tata Ruang dan Kearsipan',
                'type' => 'Surat Masuk',
                'sub_type' => null,
                'description' => 'Undangan formal tingkat provinsi mengenai digitalisasi kearsipan daerah.',
                'file_path' => $samplePdfPath,
                'file_name' => 'Undangan_Rakor_Kearsipan.pdf',
                'file_size' => $fileSize,
                'user_id' => $admin->id,
            ],
        ];

        foreach ($samples as $sample) {
            Document::firstOrCreate(
                ['document_number' => $sample['document_number']],
                $sample
            );
        }
    }
}
