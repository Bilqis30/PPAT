<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('layanans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('badge')->nullable();
            $table->string('badge_color')->default('emerald');
            $table->string('icon')->default('clipboard-list');
            $table->text('description')->nullable();
            $table->json('steps')->nullable();
            $table->json('requirements_penjual')->nullable();
            $table->json('requirements_pembeli')->nullable();
            $table->string('estimated_time')->nullable();
            $table->integer('order_num')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed initial default services so existing public data is preserved immediately
        $initialServices = [
            [
                'slug' => 'ajb',
                'title' => 'Akta Jual Beli (AJB)',
                'badge' => 'Paling Sering Digunakan',
                'badge_color' => 'emerald',
                'icon' => 'handshake',
                'description' => 'Akta otentik yang membuktikan peralihan hak atas tanah dan/atau bangunan karena proses jual beli yang sah secara hukum.',
                'steps' => json_encode([
                    'Pemeriksaan & Validasi Sertifikat di BPN: PPAT memeriksa keaslian dan status bersih sertifikat tanah (bebas sengketa, sita, atau blokir).',
                    'Pembayaran & Validasi Pajak: Penjual membayar PPh Final (2,5%) dan Pembeli membayar BPHTB (5% setelah dikurangi NPOPTKP) serta validasi ke Bapenda.',
                    'Penandatanganan AJB: Dilakukan di hadapan PPAT, dihadiri oleh Penjual (bersama suami/istri jika harta bersama), Pembeli, dan 2 orang saksi resmi.',
                    'Pendaftaran Balik Nama ke BPN: Berkas AJB didaftarkan ke Kantor Pertanahan Kabupaten Muara Enim untuk diterbitkan sertifikat atas nama pembeli baru.',
                ]),
                'requirements_penjual' => json_encode([
                    'Sertifikat Asli Tanah (SHM / SHGB / SHMSRS)',
                    'KTP Penjual dan Suami/Istri (jika sudah menikah)',
                    'Kartu Keluarga (KK) Penjual',
                    'Surat Nikah / Surat Cerai (jika status janda/duda)',
                    'Surat Persetujuan Suami/Istri (wajib hadir menandatangani)',
                    'NPWP Penjual',
                    'Asli SPPT PBB Tahun Berjalan beserta Surat Tanda Terima Setoran (STTS/Bukti Lunas)',
                ]),
                'requirements_pembeli' => json_encode([
                    'KTP Pembeli dan Suami/Istri (jika sudah menikah)',
                    'Kartu Keluarga (KK) Pembeli',
                    'NPWP Pembeli',
                    'Surat Pernyataan Tidak Melebihi Batas Maksimum Kepemilikan Tanah',
                    'Bukti Pembayaran BPHTB (Bea Perolehan Hak atas Tanah dan Bangunan) yang telah tervalidasi',
                ]),
                'estimated_time' => '1 - 2 minggu (tergantung durasi validasi pajak daerah & proses balik nama BPN)',
                'order_num' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'hibah',
                'title' => 'Akta Hibah Tanah & Bangunan',
                'badge' => 'Keluarga & Kerabat',
                'badge_color' => 'sky',
                'icon' => 'gift',
                'description' => 'Akta pemberian hak atas tanah dan bangunan dari seseorang kepada pihak lain (biasanya anak kandung/keluarga) secara cuma-cuma tanpa uang pengganti.',
                'steps' => json_encode([
                    'Pengecekan Sertifikat di Kantor Pertanahan Muara Enim.',
                    'Persetujuan Ahli Waris Lain: Memastikan pemberian hibah tidak melanggar hak mutlak (legitieme portie) ahli waris lainnya.',
                    'Penghitungan dan Validasi BPHTB Hibah di Bapenda Muara Enim.',
                    'Penandatanganan Akta Hibah di hadapan PPAT bersama seluruh pihak terkait.',
                    'Pendaftaran peralihan hak (balik nama hibah) ke Kantor Pertanahan BPN.',
                ]),
                'requirements_penjual' => json_encode([
                    'Sertifikat Asli Tanah',
                    'KTP & Kartu Keluarga (KK) Pemberi Hibah dan Pasangan',
                    'Surat Persetujuan Tertulis dari Ahli Waris lainnya',
                    'Buku Nikah / Akta Perkawinan Pemberi Hibah',
                    'NPWP Pemberi Hibah',
                    'SPPT PBB tahun berjalan + Bukti Lunas PBB',
                ]),
                'requirements_pembeli' => json_encode([
                    'KTP & KK Penerima Hibah',
                    'Akta Kelahiran Penerima Hibah (sebagai bukti hubungan sedarah)',
                    'NPWP Penerima Hibah',
                    'Bukti Setor & Validasi BPHTB Hibah',
                ]),
                'estimated_time' => '1 - 3 minggu',
                'order_num' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'waris',
                'title' => 'Peralihan Hak Karena Waris (Turun Waris)',
                'badge' => 'Hukum Waris',
                'badge_color' => 'indigo',
                'icon' => 'users',
                'description' => 'Pendaftaran peralihan hak atas tanah dari pemilik yang telah meninggal dunia (pewaris) kepada seluruh ahli waris yang sah.',
                'steps' => json_encode([
                    'Pembuatan Surat Keterangan Waris (SKW) yang dilegalisasi lurah & camat atau notaris.',
                    'Pengecekan Sertifikat Tanah Pewaris di Kantor Pertanahan.',
                    'Validasi BPHTB Waris di kantor Bapenda Muara Enim.',
                    'Pendaftaran Balik Nama Waris ke Kantor Pertanahan agar sertifikat tercatat atas nama seluruh ahli waris yang berhak.',
                ]),
                'requirements_penjual' => json_encode([
                    'Sertifikat Asli Tanah atas nama Pewaris',
                    'Surat Keterangan Kematian Pewaris (dari Rumah Sakit / Desa / Kelurahan)',
                    'Surat Keterangan Ahli Waris (SKW) sah yang ditandatangani Lurah & Camat',
                    'Buku Nikah Pewaris',
                    'SPPT PBB tahun berjalan + Bukti Lunas PBB',
                ]),
                'requirements_pembeli' => json_encode([
                    'KTP & Kartu Keluarga (KK) seluruh Ahli Waris yang berhak',
                    'Akta Kelahiran seluruh Ahli Waris',
                    'NPWP para Ahli Waris',
                    'Surat Kuasa (bila dikuasakan kepada salah satu ahli waris untuk mengurus berkas)',
                    'Bukti Setor & Validasi BPHTB Waris',
                ]),
                'estimated_time' => '2 - 4 minggu',
                'order_num' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'apht',
                'title' => 'Akta Pemberian Hak Tanggungan (APHT)',
                'badge' => 'Jaminan Kredit Bank',
                'badge_color' => 'amber',
                'icon' => 'shield-check',
                'description' => 'Akta pembebanan hak tanggungan atas tanah sebagai jaminan pelunasan utang/kredit kepada pihak perbankan atau lembaga keuangan.',
                'steps' => json_encode([
                    'Penerbitan Surat Penegasan Persetujuan Penyediaan Kredit (SP3K) atau Perjanjian Kredit dari Bank.',
                    'Pengecekan Sertifikat Elektronik di Kantor Pertanahan.',
                    'Penandatanganan APHT di hadapan PPAT oleh Debitur/Pemilik Jaminan dan Pejabat Bank.',
                    'Pendaftaran Hak Tanggungan Elektronik (HT-el) ke sistem BPN untuk penerbitan Sertifikat Hak Tanggungan.',
                ]),
                'requirements_penjual' => json_encode([
                    'Sertifikat Asli Tanah yang dijadikan jaminan kredit',
                    'KTP & KK Pemilik Tanah beserta Pasangan (Suami/Istri)',
                    'Surat Persetujuan Pasangan (wajib hadir bertanda tangan)',
                    'Surat Perjanjian Kredit / SP3K dari Bank',
                    'NPWP Pemohon / Debitur',
                    'SPPT PBB tahun berjalan + Bukti Pembayaran Lunas',
                ]),
                'requirements_pembeli' => json_encode([
                    'Identitas Pejabat Bank yang ditunjuk mewakili kreditur',
                    'Surat Kuasa Bank (jika dikuasakan)',
                    'Kelengkapan berkas legalitas dari pihak perbankan',
                ]),
                'estimated_time' => '3 - 7 hari kerja (via sistem terintegrasi HT-el ATR/BPN)',
                'order_num' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'aphb',
                'title' => 'Akta Pembagian Hak Bersama (APHB)',
                'badge' => 'Pemisahan Kepemilikan',
                'badge_color' => 'purple',
                'icon' => 'split',
                'description' => 'Akta yang dibuat untuk membagi tanah yang dimiliki bersama (misal oleh beberapa ahli waris) menjadi milik satu atau beberapa orang tertentu.',
                'steps' => json_encode([
                    'Musyawarah kesepakatan pembagian antar seluruh pemegang hak bersama.',
                    'Pengecekan status sertifikat tanah di BPN.',
                    'Penandatanganan APHB di hadapan PPAT dihadiri semua pemilik hak bersama.',
                    'Pendaftaran pendaftaran perubahan hak ke Kantor Pertanahan.',
                ]),
                'requirements_penjual' => json_encode([
                    'Sertifikat Asli Tanah atas nama bersama / warisan',
                    'KTP & Kartu Keluarga seluruh pemegang hak bersama',
                    'Surat Kesepakatan Bersama tentang Pembagian Hak atas Tanah',
                    'SPPT PBB tahun berjalan + Bukti Lunas',
                ]),
                'requirements_pembeli' => json_encode([
                    'KTP & Kartu Keluarga pihak yang akan menerima bagian tertentu',
                    'NPWP para pihak terkait',
                    'Bukti setor BPHTB jika timbul kewajiban pajak pembagian',
                ]),
                'estimated_time' => '2 - 3 minggu',
                'order_num' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'cek-sertifikat',
                'title' => 'Pengecekan Sertifikat & SKPT BPN',
                'badge' => 'Keamanan Transaksi',
                'badge_color' => 'teal',
                'icon' => 'search-check',
                'description' => 'Pengecekan resmi ke Kantor Pertanahan (BPN) untuk menjamin sertifikat asli, terdaftar di buku tanah, dan tidak sedang disengketakan atau disita.',
                'steps' => json_encode([
                    'Penyerahan asli sertifikat dan identitas pemilik tanah ke kantor PPAT.',
                    'Pengajuan pengecekan elektronik melalui portal resmi mitra ATR/BPN RI.',
                    'Hasil catatan resmi BPN keluar (membuktikan sertifikat sah dan siap ditransaksikan).',
                ]),
                'requirements_penjual' => json_encode([
                    'Asli Sertifikat Hak Atas Tanah (SHM / SHGB / SHP)',
                    'Fotokopi KTP & KK Pemilik Terdaftar di Sertifikat',
                    'Surat Permohonan Pengecekan Sertifikat',
                    'Fotokopi SPPT PBB tahun terakhir',
                ]),
                'requirements_pembeli' => json_encode([
                    'Fotokopi KTP Calon Pembeli (jika pengecekan dalam rangka rencana jual beli)',
                ]),
                'estimated_time' => '1 - 2 hari kerja (tergantung sistem sinkronisasi ATR/BPN)',
                'order_num' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('layanans')->insert($initialServices);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanans');
    }
};
