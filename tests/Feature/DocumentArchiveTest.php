<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentArchiveTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $pimpinan;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Storage::fake('public');

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->pimpinan = User::create([
            'name' => 'Pimpinan Test',
            'email' => 'pimpinan@test.com',
            'password' => bcrypt('password'),
            'role' => 'pimpinan',
        ]);
    }

    public function test_public_landing_page_can_be_accessed(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('PPAT Iwan Kurniawan, SH., MH., M.Kn');
        $response->assertSee('37/SK-HR.03.04.PPAT/III/2023');
        $response->assertSee('Kabupaten Muara Enim');
        $response->assertSee('Hubungi Kantor');
        $response->assertDontSee('Login Petugas');
    }

    public function test_separated_login_portal_is_accessible_via_dedicated_routes(): void
    {
        $response = $this->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Portal Masuk Internal');
        $response->assertSee('Admin &amp; Pimpinan', false);

        $loginResponse = $this->get('/login');
        $loginResponse->assertRedirect('/admin');

        $portalResponse = $this->get('/portal');
        $portalResponse->assertRedirect('/admin');
    }

    public function test_guest_accessing_protected_routes_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin');

        $response = $this->get('/pimpinan/dashboard');
        $response->assertRedirect('/admin');
    }

    public function test_admin_can_login_and_access_admin_dashboard(): void
    {
        $response = $this->post('/admin', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin);

        $dashResponse = $this->actingAs($this->admin)->get('/admin/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Dashboard Pengelola Arsip');
    }

    public function test_pimpinan_is_forbidden_from_admin_routes(): void
    {
        // Pimpinan accesses admin dashboard -> 403
        $response = $this->actingAs($this->pimpinan)->get('/admin/dashboard');
        $response->assertStatus(403);

        // Pimpinan accesses admin upload form -> 403
        $response = $this->actingAs($this->pimpinan)->get('/admin/documents/create');
        $response->assertStatus(403);

        // Pimpinan tries to upload -> 403
        $response = $this->actingAs($this->pimpinan)->post('/admin/documents', []);
        $response->assertStatus(403);
    }

    public function test_pimpinan_can_access_monitoring_and_dashboard(): void
    {
        $response = $this->actingAs($this->pimpinan)->get('/pimpinan/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Executive Summary');

        $response = $this->actingAs($this->pimpinan)->get('/pimpinan/monitoring');
        $response->assertStatus(200);
        $response->assertSee('Ruang Pemantauan Arsip Global');
    }

    public function test_admin_can_upload_document_with_dynamic_subtype(): void
    {
        $fakePdf = UploadedFile::fake()->create('laporan_bpn_test.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->post('/admin/documents', [
            'document_number' => 'BPN/2026/001',
            'document_date' => '2026-09-28',
            'title' => 'Laporan Rekapitulasi Lahan BPN',
            'type' => 'Laporan',
            'sub_type' => 'Laporan BPN',
            'description' => 'Laporan pengujian validasi upload.',
            'file' => $fakePdf,
        ]);

        $response->assertRedirect(route('admin.documents.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'document_number' => 'BPN/2026/001',
            'type' => 'Laporan',
            'sub_type' => 'Laporan BPN',
        ]);

        $doc = Document::where('document_number', 'BPN/2026/001')->first();
        $this->assertNotNull($doc);
        Storage::disk('public')->assertExists($doc->file_path);
    }

    public function test_admin_can_upload_berkas_without_document_number(): void
    {
        $fakePdf = UploadedFile::fake()->create('ktp_pemohon.pdf', 300, 'application/pdf');

        $response = $this->actingAs($this->admin)->post('/admin/documents', [
            // No document_number passed
            'document_date' => '2026-09-28',
            'title' => 'Berkas KTP & Kartu Keluarga Pemohon',
            'type' => 'Berkas',
            'description' => 'Fotokopi KTP dan KK pihak pemohon.',
            'file' => $fakePdf,
        ]);

        $response->assertRedirect(route('admin.documents.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'title' => 'Berkas KTP & Kartu Keluarga Pemohon',
            'type' => 'Berkas',
            'document_number' => null,
        ]);
    }

    public function test_upload_requires_subtype_when_type_is_laporan(): void
    {
        $fakePdf = UploadedFile::fake()->create('dok.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->post('/admin/documents', [
            'document_number' => 'LAP/TEST/002',
            'document_date' => '2026-09-28',
            'title' => 'Laporan Tanpa Subtipe',
            'type' => 'Laporan',
            // Missing sub_type
            'file' => $fakePdf,
        ]);

        $response->assertSessionHasErrors('sub_type');
    }

    public function test_admin_can_delete_document_and_physical_pdf_is_removed(): void
    {
        $fakePdf = UploadedFile::fake()->create('to_delete.pdf', 200, 'application/pdf');
        $storedPath = $fakePdf->store('documents', 'local');

        $doc = Document::create([
            'document_number' => 'DEL/001',
            'document_date' => '2026-09-28',
            'title' => 'Dokumen Untuk Dihapus',
            'type' => 'Berkas',
            'file_path' => $storedPath,
            'file_name' => 'to_delete.pdf',
            'file_size' => 200,
            'user_id' => $this->admin->id,
        ]);

        Storage::disk('local')->assertExists($storedPath);

        $response = $this->actingAs($this->admin)->delete(route('admin.documents.destroy', $doc));
        $response->assertRedirect();

        $this->assertDatabaseMissing('documents', ['id' => $doc->id]);
        Storage::disk('local')->assertMissing($storedPath);
    }

    public function test_pimpinan_can_preview_and_download_document(): void
    {
        $fakePdf = UploadedFile::fake()->create('preview_doc.pdf', 200, 'application/pdf');
        $storedPath = $fakePdf->store('documents', 'local');

        $doc = Document::create([
            'document_number' => 'PREV/001',
            'document_date' => '2026-09-28',
            'title' => 'Dokumen Preview Pimpinan',
            'type' => 'Surat Masuk',
            'file_path' => $storedPath,
            'file_name' => 'preview_doc.pdf',
            'file_size' => 200,
            'user_id' => $this->admin->id,
        ]);

        // Preview route
        $response = $this->actingAs($this->pimpinan)->get(route('documents.preview', $doc));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');

        // Download route
        $response = $this->actingAs($this->pimpinan)->get(route('documents.download', $doc));
        $response->assertStatus(200);
    }

    public function test_login_rate_limiting_protects_against_brute_force(): void
    {
        // 5 consecutive failed login attempts
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/admin', [
                'email' => 'admin@test.com',
                'password' => 'wrong-password',
            ]);
            $response->assertSessionHasErrors('email');
        }

        // 6th attempt should be blocked by Rate Limiter
        $response = $this->post('/admin', [
            'email' => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        // Either caught by AuthController RateLimiter or route throttle
        if ($response->status() === 429) {
            $response->assertStatus(429);
        } else {
            $response->assertSessionHasErrors('email');
            $this->assertStringContainsString('Terlalu banyak percobaan', session('errors')->first('email'));
        }
    }

    public function test_security_headers_are_attached_to_responses(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_upload_rejects_non_pdf_disguised_files(): void
    {
        // Malicious file pretending to be text/script
        $fakePhp = UploadedFile::fake()->create('malicious.php', 100, 'application/x-php');

        $response = $this->actingAs($this->admin)->post('/admin/documents', [
            'document_number' => 'MAL/001',
            'document_date' => '2026-09-28',
            'title' => 'Berkas Jahat',
            'type' => 'Berkas',
            'file' => $fakePhp,
        ]);

        $response->assertSessionHasErrors('file');
    }

    public function test_pimpinan_cannot_delete_document(): void
    {
        $fakePdf = UploadedFile::fake()->create('test.pdf', 100, 'application/pdf');
        $storedPath = $fakePdf->store('documents', 'local');

        $doc = Document::create([
            'document_number' => 'DOC/001',
            'document_date' => '2026-09-28',
            'title' => 'Dokumen Aman',
            'type' => 'Berkas',
            'file_path' => $storedPath,
            'file_name' => 'test.pdf',
            'file_size' => 100,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->pimpinan)->delete(route('admin.documents.destroy', $doc));
        $response->assertStatus(403);

        $this->assertDatabaseHas('documents', ['id' => $doc->id]);
    }

    public function test_admin_can_upload_surat_masuk_with_tanggal_acara(): void
    {
        $fakePdf = UploadedFile::fake()->create('surat_masuk_undangan.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->post('/admin/documents', [
            'document_number' => '005/SM/BPN/IV/2026',
            'document_date' => '2026-10-03',
            'tanggal_acara' => '2026-10-05',
            'title' => 'Surat Undangan Rapat Koordinasi Wilayah',
            'type' => 'Surat Masuk',
            'description' => 'Undangan rapat dinas.',
            'file' => $fakePdf,
        ]);

        $response->assertRedirect(route('admin.documents.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'document_number' => '005/SM/BPN/IV/2026',
            'type' => 'Surat Masuk',
        ]);

        $doc = Document::where('document_number', '005/SM/BPN/IV/2026')->first();
        $this->assertNotNull($doc);
        $this->assertEquals('2026-10-05', $doc->tanggal_acara->format('Y-m-d'));
    }

    public function test_send_event_reminder_command_executes_synchronous_http_whatsapp_request(): void
    {
        Http::fake([
            '*' => Http::response(['status' => true, 'message' => 'Sent'], 200),
        ]);

        config([
            'services.whatsapp.url' => 'https://api.fonnte.com/send',
            'services.whatsapp.token' => 'test-token-123',
            'services.whatsapp.recipient' => '081234567890',
        ]);

        $h2Date = Carbon::now()->addDays(2)->toDateString();

        $fakePdf = UploadedFile::fake()->create('undangan_h2.pdf', 300, 'application/pdf');
        $storedPath = $fakePdf->store('documents', 'local');

        Document::create([
            'document_number' => '007/SM/UND/2026',
            'document_date' => '2026-10-03',
            'tanggal_acara' => $h2Date,
            'title' => 'Rapat Pleno Penyusunan Laporan H-2',
            'type' => 'Surat Masuk',
            'description' => 'Rapat pleno koordinasi.',
            'file_path' => $storedPath,
            'file_name' => 'undangan_h2.pdf',
            'file_size' => 300,
            'user_id' => $this->admin->id,
        ]);

        $this->artisan('notification:send-event-reminder')
            ->expectsOutputToContain('Ditemukan 1 agenda Surat Masuk H-2.')
            ->expectsOutputToContain('✓ Notifikasi WA berhasil diproses Gateway')
            ->assertExitCode(0);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.fonnte.com/send'
                && $request->hasHeader('Authorization', 'test-token-123')
                && str_contains($request['message'], 'Rapat Pleno Penyusunan Laporan H-2');
        });
    }

    public function test_admin_can_access_and_update_whatsapp_settings(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.whatsapp'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan WhatsApp Gateway');

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.settings.whatsapp.update'), [
            'wa_gateway_url' => 'https://api.fonnte.com/send',
            'wa_gateway_token' => 'my-custom-token-xyz',
            'wa_recipient_number' => '081299998888',
            'wa_is_active' => '1',
            'wa_reminder_days' => '3',
            'wa_reminder_time' => '08:30',
        ]);

        $updateResponse->assertRedirect(route('admin.settings.whatsapp'));
        $updateResponse->assertSessionHas('success');

        $this->assertEquals('https://api.fonnte.com/send', Setting::get('wa_gateway_url'));
        $this->assertEquals('my-custom-token-xyz', Setting::get('wa_gateway_token'));
        $this->assertEquals('081299998888', Setting::get('wa_recipient_number'));
        $this->assertEquals('1', Setting::get('wa_is_active'));
        $this->assertEquals('3', Setting::get('wa_reminder_days'));
        $this->assertEquals('08:30', Setting::get('wa_reminder_time'));
    }

    public function test_pimpinan_cannot_access_whatsapp_settings(): void
    {
        $response = $this->actingAs($this->pimpinan)->get(route('admin.settings.whatsapp'));
        $response->assertStatus(403);
    }

    public function test_send_event_reminder_skipped_when_whatsapp_disabled(): void
    {
        Setting::set('wa_is_active', '0');

        $this->artisan('notification:send-event-reminder')
            ->expectsOutputToContain('Notifikasi WhatsApp pengingat acara sedang dinonaktifkan di Pengaturan Sistem.')
            ->assertExitCode(0);
    }

    public function test_admin_can_access_edit_document_form(): void
    {
        $document = Document::create([
            'document_number' => '123/SM/2026',
            'document_date' => '2026-10-01',
            'title' => 'Surat Undangan Test Edit',
            'type' => 'Surat Masuk',
            'file_path' => 'documents/dummy.pdf',
            'file_name' => 'dummy.pdf',
            'file_size' => 1024,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.documents.edit', $document));
        $response->assertStatus(200);
        $response->assertSee('Edit Dokumen Arsip');
        $response->assertSee('Surat Undangan Test Edit');
    }

    public function test_admin_can_update_document_metadata(): void
    {
        $document = Document::create([
            'document_number' => '100/SK/2026',
            'document_date' => '2026-10-01',
            'title' => 'Judul Lama',
            'type' => 'Surat Keluar',
            'file_path' => 'documents/dummy.pdf',
            'file_name' => 'dummy.pdf',
            'file_size' => 1024,
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.documents.update', $document), [
            'document_number' => '999/SK/2026',
            'document_date' => '2026-10-10',
            'title' => 'Judul Baru Diperbarui',
            'type' => 'Surat Keluar',
            'description' => 'Deskripsi update test',
        ]);

        $response->assertRedirect(route('documents.show', $document));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'title' => 'Judul Baru Diperbarui',
            'document_number' => '999/SK/2026',
        ]);
    }
}
