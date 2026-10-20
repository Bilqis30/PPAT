<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SQLiteAntiGravityArchitectureTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');

        $this->admin = User::create([
            'name' => 'Admin AntiGravity',
            'email' => 'admin.antigravity@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }

    /**
     * Test 1: Verify database indexes on SQLite tables for fast querying and filtering.
     */
    public function test_sqlite_performance_indexes_exist_on_documents_and_drive_tables(): void
    {
        // SQLite index list inspection
        $documentIndexes = collect(DB::select("PRAGMA index_list('documents')"))->pluck('name')->all();

        $this->assertTrue(Schema::hasTable('documents'));
        $this->assertTrue(Schema::hasColumns('documents', [
            'document_number', 'document_date', 'type', 'sub_type', 'file_path', 'created_at',
        ]));

        // Ensure key index exists
        $hasDateOrCompositeIndex = collect($documentIndexes)->contains(function ($name) {
            return str_contains($name, 'document_date') || str_contains($name, 'type') || str_contains($name, 'created_at');
        });
        $this->assertTrue($hasDateOrCompositeIndex, 'Performance indexes must exist on documents table.');
    }

    /**
     * Test 2: File is saved in physical storage/app/public/archives and only string path is saved in SQLite (No BLOB).
     */
    public function test_document_file_stored_physically_in_public_archives_and_db_contains_only_path(): void
    {
        $file = UploadedFile::fake()->create('arsip_resmi.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'document_number' => 'DOC/ARCH/2026/01',
            'document_date' => '2026-10-08',
            'title' => 'Arsip Resmi Tanah Muara Enim',
            'type' => 'Surat Masuk',
            'description' => 'Penyimpanan lokal anti-bloatware',
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.documents.index'));

        $doc = Document::where('document_number', 'DOC/ARCH/2026/01')->firstOrFail();

        // 1. Database column stores only relative path string, never raw binary BLOB
        $this->assertIsString($doc->file_path);
        $this->assertStringStartsWith('archives/', $doc->file_path);
        $this->assertLessThan(255, strlen($doc->file_path));

        // 2. Physical file exists on public disk (storage/app/public/archives)
        Storage::disk('public')->assertExists($doc->file_path);
    }

    /**
     * Test 3: Pagination is enforced on documents list to prevent memory bloat on large datasets.
     */
    public function test_archive_listing_is_paginated_to_prevent_memory_bloat(): void
    {
        Document::factory()->count(15)->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.documents.index'));
        $response->assertStatus(200);

        // Paginated collection in view
        $paginatedDocs = $response->viewData('documents');
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginatedDocs);
        $this->assertEquals(10, $paginatedDocs->perPage());
        $this->assertCount(10, $paginatedDocs->items());
    }

    /**
     * Test 4: Eager loading prevents N+1 query problem on relationships.
     */
    public function test_archive_listing_uses_eager_loading_to_prevent_n_plus_one_queries(): void
    {
        Document::factory()->count(5)->create([
            'user_id' => $this->admin->id,
        ]);

        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->actingAs($this->admin)->get(route('admin.documents.index'));
        $response->assertStatus(200);

        $documents = $response->viewData('documents');
        foreach ($documents as $doc) {
            // Relation is already eager-loaded in memory, accessing user doesn't execute extra queries
            $this->assertTrue($doc->relationLoaded('user'));
            $this->assertNotNull($doc->user->name);
        }

        DB::disableQueryLog();
    }

    /**
     * Test 5: Strict validation rejects dangerous scripts (.php, .exe, .sh, .phtml).
     */
    public function test_upload_strictly_rejects_dangerous_executable_and_script_files(): void
    {
        $maliciousPhp = UploadedFile::fake()->create('exploit.php', 50, 'application/x-php');

        $response = $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'document_number' => 'DANGER/001',
            'document_date' => '2026-10-08',
            'title' => 'Percobaan Upload Script',
            'type' => 'Berkas',
            'file' => $maliciousPhp,
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseMissing('documents', ['document_number' => 'DANGER/001']);
    }

    /**
     * Test 6: Strict validation allows valid document formats (.pdf, .docx, .jpg).
     */
    public function test_upload_accepts_valid_documents_such_as_docx_and_jpg(): void
    {
        $docxFile = UploadedFile::fake()->create('draft_akta.docx', 200, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $response = $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'document_number' => 'DOCX/2026/01',
            'document_date' => '2026-10-08',
            'title' => 'Draft Akta Notaris',
            'type' => 'Surat Keluar',
            'file' => $docxFile,
        ]);

        $response->assertRedirect(route('admin.documents.index'));
        $this->assertDatabaseHas('documents', ['document_number' => 'DOCX/2026/01']);
    }

    /**
     * Test 7: Automatic File Deletion Cleanup removes physical file when Eloquent model is deleted.
     */
    public function test_file_deletion_cleanup_automatically_removes_physical_storage_file(): void
    {
        $file = UploadedFile::fake()->create('arsip_sementara.pdf', 100, 'application/pdf');
        $storedPath = $file->store('archives', 'public');

        $doc = Document::create([
            'document_number' => 'TEMP/DEL/001',
            'document_date' => '2026-10-08',
            'title' => 'Dokumen Bersih Sampah',
            'type' => 'Berkas',
            'file_path' => $storedPath,
            'file_name' => 'arsip_sementara.pdf',
            'file_size' => 100,
            'user_id' => $this->admin->id,
        ]);

        Storage::disk('public')->assertExists($storedPath);

        // Delete model
        $doc->delete();

        // Physical file must be automatically erased by booted deleting hook
        $this->assertDatabaseMissing('documents', ['id' => $doc->id]);
        Storage::disk('public')->assertMissing($storedPath);
    }
}
