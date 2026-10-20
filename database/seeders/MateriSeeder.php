<?php

namespace Database\Seeders;

use App\Models\Materi;
use App\Models\User;
use Illuminate\Database\Seeder;

class MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $items = [
            [
                'category' => 'Artikel',
                'title' => 'Merawat Jantung Batanghari Sembilan: Kolaborasi Nyata Menjaga Hutan dan Air...',
                'slug' => 'merawat-jantung-batanghari-sembilan-kolaborasi-nyata-menjaga-hutan-dan-air',
                'cover_image' => 'images/materi/batanghari_sembilan.jpg',
                'excerpt' => 'Upaya kolaboratif multi-pihak dalam melestarikan ekosistem hulu sungai dan hutan lindung di wilayah Batanghari Sembilan demi keberlanjutan air dan lingkungan hidup masyarakat.',
                'content' => 'Wilayah aliran sungai Batanghari Sembilan merupakan urat nadi ekologis yang menopang kehidupan jutaan masyarakat di Sumatera Selatan dan sekitarnya. Melestarikan ekosistem hutan penyangga di bagian hulu bukan lagi sekadar pilihan, melainkan keharusan mendesak demi menjamin ketersediaan air bersih, pencegahan erosi, dan mitigasi bencana hidrometeorologis.

Melalui sinergi antara instansi berwenang, praktisi hukum pertanahan, masyarakat adat, serta komunitas penggiat lingkungan, langkah-langkah nyata terus digalakkan. Salah satu aspek krusial adalah kepastian tata batas kawasan hutan dan tertib administrasi pertanahan di wilayah sempadan sungai untuk mencegah alih fungsi lahan yang merusak keseimbangan alam.',
                'published_at' => '2026-09-02',
                'author_name' => 'PPAT Iwan Kurniawan',
                'user_id' => $admin?->id,
            ],
            [
                'category' => 'Artikel',
                'title' => 'Agroforestri di Lahan Bekas Tambang: Tantangan dan Peluang Ekonomi Sirkular Muara Enim',
                'slug' => 'agroforestri-di-lahan-bekas-tambang-tantangan-dan-peluang-ekonomi-sirkular-muara-enim',
                'cover_image' => 'images/materi/agroforestri_tambang.jpg',
                'excerpt' => 'Transformasi lahan pasca-tambang di Kabupaten Muara Enim menuju model agroforestri produktif sebagai pendorong ekonomi sirkular dan pemulihan daya dukung lingkungan.',
                'content' => 'Kabupaten Muara Enim yang kaya akan sumber daya mineral kini menghadapi babak baru dalam pengelolaan bentang alam pasca-tambang. Reklamasi dan revegetasi tidak hanya diarahkan untuk menghijaukan kembali area tambang, melainkan diintegrasikan ke dalam skema agroforestri berkelanjutan.

Penerapan kombinasi tanaman kehutanan lokal dengan tanaman komoditas bernilai ekonomis terbukti mampu memulihkan kesuburan tanah sekaligus membuka lapangan kerja baru bagi warga sekitar. Dari sudut pandang hukum dan kepemilikan tanah, pemanfaatan kembali lahan bekas tambang memerlukan kejelasan status hak atas tanah serta dokumentasi kearsipan yang tertib dan transparan.',
                'published_at' => '2026-09-01',
                'author_name' => 'PPAT Iwan Kurniawan',
                'user_id' => $admin?->id,
            ],
            [
                'category' => 'Sosialisasi',
                'title' => 'Sosialisasi Penerapan Sertifikat Tanah Elektronik di Kabupaten Muara Enim',
                'slug' => 'sosialisasi-penerapan-sertifikat-tanah-elektronik-di-kabupaten-muara-enim',
                'cover_image' => 'images/materi/batanghari_sembilan.jpg',
                'excerpt' => 'Petunjuk teknis dan tahapan transisi sertifikat tanah fisik (buku tanah) menuju format sertifikat elektronik yang lebih aman dan terintegrasi.',
                'content' => 'Kementerian Agraria dan Tata Ruang/Badan Pertanahan Nasional (ATR/BPN) terus memperluas implementasi layanan sertifikat tanah elektronik di seluruh wilayah Republik Indonesia, termasuk di Kabupaten Muara Enim.

Melalui sosialisasi ini, Kantor PPAT Iwan Kurniawan menyampaikan beberapa poin penting yang perlu dipahami oleh masyarakat:

1. Keamanan Dokumen: Sertifikat elektronik menggunakan pengamanan kriptografi bersertifikasi Balai Sertifikasi Elektronik (BSrE), sehingga meminimalisir risiko pemalsuan, duplikasi, atau kerusakan fisik akibat bencana.
2. Tidak Mengganti Hak Secara Tiba-tiba: Sertifikat fisik lama yang dipegang masyarakat tetap sah dan berlaku. Penggantian ke sertifikat elektronik dilakukan secara bertahap saat terjadi perbuatan hukum (misal jual beli, hibah, pewarisan, atau peningkatan hak).
3. Kemudahan Akses: Pemilik tanah dapat memantau dan mengakses dokumen kepemilikannya secara fleksibel melalui aplikasi resmi Sentuh Tanahku.',
                'published_at' => '2026-09-03',
                'author_name' => 'PPAT Iwan Kurniawan',
                'user_id' => $admin?->id,
            ],
        ];

        foreach ($items as $item) {
            Materi::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
