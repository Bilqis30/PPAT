<?php

namespace Tests\Feature;

use App\Models\Materi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_contains_comprehensive_seo_meta_tags_and_schema(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<meta name="keywords"', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('<meta name="geo.placename" content="Muara Enim">', false);
        $response->assertSee('<meta property="og:site_name"', false);
        $response->assertSee('<meta name="twitter:card"', false);
        $response->assertSee('"@type": ["LegalService", "LocalBusiness", "ProfessionalService"]', false);
        $response->assertSee('Kantor PPAT Iwan Kurniawan, SH., MH., M.Kn', false);
        $response->assertSee('Kabupaten Muara Enim', false);
    }

    public function test_sitemap_xml_returns_valid_xml_with_public_urls(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        Materi::create([
            'title' => 'Panduan Akta Jual Beli Tanah Muara Enim',
            'slug' => 'panduan-ajb-tanah-muara-enim',
            'content' => 'Isi artikel lengkap mengenai panduan AJB di Muara Enim.',
            'category' => 'Artikel',
            'published_at' => now(),
            'author_name' => 'PPAT Iwan Kurniawan',
            'user_id' => $user->id,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false);
        $response->assertSee(route('home'), false);
        $response->assertSee(route('layanan.index'), false);
        $response->assertSee(route('materi.index'), false);
        $response->assertSee(route('materi.show', 'panduan-ajb-tanah-muara-enim'), false);
    }

    public function test_materi_detail_contains_article_structured_data(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $materi = Materi::create([
            'title' => 'Tata Cara Pengecekan Sertifikat Tanah BPN',
            'slug' => 'tata-cara-pengecekan-sertifikat-tanah-bpn',
            'excerpt' => 'Panduan lengkap cara cek sertifikat tanah ke kantor BPN Muara Enim.',
            'content' => 'Penjelasan rinci tahapan validasi dan pengecekan sertifikat...',
            'category' => 'Artikel',
            'published_at' => now(),
            'author_name' => 'Iwan Kurniawan, SH., MH., M.Kn',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('materi.show', $materi->slug));

        $response->assertStatus(200);
        $response->assertSee('"@type": "Article"', false);
        $response->assertSee('Tata Cara Pengecekan Sertifikat Tanah BPN', false);
        $response->assertSee('<meta property="og:type" content="article">', false);
    }
}
