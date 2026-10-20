<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayananTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_access_layanan_guide_page(): void
    {
        $response = $this->get(route('layanan.index'));

        $response->assertStatus(200);
        $response->assertSee('Panduan Cara &amp; Persyaratan', false);
        $response->assertSee('Akta Jual Beli (AJB)');
        $response->assertSee('Akta Hibah');
        $response->assertSee('Turun Waris');
        $response->assertSee('Akta Pemberian Hak Tanggungan (APHT)');
        $response->assertSee('Sertifikat Asli Tanah');
        $response->assertSee('Alur &amp; Langkah Pengajuan', false);
        $response->assertSee('logo-ppat.jpg');
        $response->assertDontSee('0823-8005-5885');
        $response->assertDontSee('6282380055885');
    }

    public function test_navbar_across_pages_has_panduan_layanan_link(): void
    {
        $this->get('/')->assertSee(route('layanan.index'));
        $this->get(route('materi.index'))->assertSee(route('layanan.index'));
    }
}
