<?php

namespace Tests\Feature;

use App\Models\Materi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MateriTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_landing_page_does_not_contain_fungsi_sistem_kearsipan_and_has_materi_link(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Fungsi Sistem Kearsipan Digital');
        $response->assertSee('Materi');
        $response->assertSee(route('materi.index'));
        $response->assertSee('logo-ppat.jpg');
        $response->assertDontSee('0823-8005-5885');
    }

    public function test_public_user_can_view_materi_index_page(): void
    {
        $response = $this->get(route('materi.index'));

        $response->assertStatus(200);
        $response->assertSee('Materi &amp; Artikel', false);
        $response->assertSee('Merawat Jantung Batanghari Sembilan', false);
        $response->assertSee('Agroforestri di Lahan Bekas Tambang', false);
        $response->assertSee('Baca');
        $response->assertDontSee('Tulis / Upload Materi');
        $response->assertDontSee('Tulis Materi');
        $response->assertSee('logo-ppat.jpg');
        $response->assertDontSee('0823-8005-5885');
    }

    public function test_public_user_can_view_single_materi_detail(): void
    {
        $materi = Materi::first();
        $this->assertNotNull($materi);

        $response = $this->get(route('materi.show', $materi->slug));

        $response->assertStatus(200);
        $response->assertSee($materi->title);
    }

    public function test_guest_cannot_access_admin_materi_pages(): void
    {
        $response = $this->get(route('admin.materi.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('admin.materi.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_materi_management_and_create_page(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.materi.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get(route('admin.materi.create'));
        $response->assertStatus(200);
        $response->assertSee('Formulir Penulisan Materi &amp; Upload', false);
    }

    public function test_admin_can_store_new_materi_with_cover_image(): void
    {
        Storage::fake('public');
        $admin = User::where('role', 'admin')->first();

        $image = UploadedFile::fake()->image('cover_test.jpg', 800, 600);

        $data = [
            'title' => 'Penyuluhan Hak Tanggungan Elektronik',
            'category' => 'Sosialisasi',
            'published_at' => '2026-10-05',
            'author_name' => 'PPAT Iwan Kurniawan',
            'cover_image' => $image,
            'excerpt' => 'Panduan lengkap mengenai pendaftaran hak tanggungan elektronik di BPN.',
            'content' => 'Hak Tanggungan Elektronik (HT-el) adalah sistem pelayanan pendaftaran hak tanggungan terintegrasi berbasis digital.',
        ];

        $response = $this->actingAs($admin)->post(route('admin.materi.store'), $data);

        $response->assertRedirect(route('admin.materi.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('materis', [
            'title' => 'Penyuluhan Hak Tanggungan Elektronik',
            'category' => 'Sosialisasi',
        ]);

        $created = Materi::where('title', 'Penyuluhan Hak Tanggungan Elektronik')->first();
        $this->assertNotNull($created->cover_image);
        Storage::disk('public')->assertExists($created->cover_image);
    }

    public function test_admin_can_update_and_delete_materi(): void
    {
        $admin = User::where('role', 'admin')->first();
        $materi = Materi::first();

        // Update
        $response = $this->actingAs($admin)->put(route('admin.materi.update', $materi), [
            'title' => 'Judul Artikel Diperbarui',
            'category' => 'Artikel',
            'published_at' => '2026-10-05',
            'content' => 'Konten artikel setelah diedit.',
        ]);

        $response->assertRedirect(route('admin.materi.index'));
        $this->assertDatabaseHas('materis', [
            'id' => $materi->id,
            'title' => 'Judul Artikel Diperbarui',
        ]);

        // Delete
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.materi.destroy', $materi));
        $deleteResponse->assertRedirect(route('admin.materi.index'));
        $this->assertDatabaseMissing('materis', [
            'id' => $materi->id,
        ]);
    }
}
