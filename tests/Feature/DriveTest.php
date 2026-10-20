<?php

namespace Tests\Feature;

use App\Models\DriveFile;
use App\Models\DriveFolder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DriveTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $pimpinan;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

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

    public function test_admin_can_access_drive_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.drive.index'));

        $response->assertStatus(200);
        $response->assertSee('Drive Berkas &amp; Proyek', false);
    }

    public function test_admin_can_create_drive_folder(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.drive.folders.store'), [
            'name' => 'Berkas Proyek Jalan Tol',
            'description' => 'Dokumen pembebasan lahan 2026',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('drive_folders', [
            'name' => 'Berkas Proyek Jalan Tol',
        ]);
    }

    public function test_admin_can_upload_file_into_folder(): void
    {
        $folder = DriveFolder::create([
            'name' => 'Folder Akta',
            'user_id' => $this->admin->id,
        ]);

        $fakeFile = UploadedFile::fake()->create('akta_jual_beli.pdf', 1500, 'application/pdf');

        $response = $this->actingAs($this->admin)->postJson(route('admin.drive.upload'), [
            'file' => $fakeFile,
            'folder_id' => $folder->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('drive_files', [
            'name' => 'akta_jual_beli.pdf',
            'folder_id' => $folder->id,
        ]);
    }

    public function test_admin_can_upload_batch_files_into_folder(): void
    {
        $folder = DriveFolder::create([
            'name' => 'Folder Batch',
            'user_id' => $this->admin->id,
        ]);

        $file1 = UploadedFile::fake()->create('dokumen_1.pdf', 1000, 'application/pdf');
        $file2 = UploadedFile::fake()->create('dokumen_2.pdf', 1200, 'application/pdf');

        $response = $this->actingAs($this->admin)->postJson(route('admin.drive.upload'), [
            'files' => [$file1, $file2],
            'folder_id' => $folder->id,
            'relative_paths' => ['Folder Batch/dokumen_1.pdf', 'Folder Batch/dokumen_2.pdf'],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 2,
        ]);

        $this->assertDatabaseHas('drive_files', ['name' => 'dokumen_1.pdf']);
        $this->assertDatabaseHas('drive_files', ['name' => 'dokumen_2.pdf']);
    }

    public function test_upload_with_relative_path_auto_creates_subfolder_hierarchy(): void
    {
        $rootFolder = DriveFolder::create([
            'name' => 'Proyek Muara Enim',
            'user_id' => $this->admin->id,
        ]);

        $fakeFile = UploadedFile::fake()->create('ktp_pemohon.pdf', 800, 'application/pdf');

        // Simulating webkitRelativePath from folder drag-and-drop
        $response = $this->actingAs($this->admin)->postJson(route('admin.drive.upload'), [
            'file' => $fakeFile,
            'folder_id' => $rootFolder->id,
            'relative_path' => 'Proyek Muara Enim/Warkah/Identitas/ktp_pemohon.pdf',
        ]);

        $response->assertStatus(200);

        // Check that subfolders "Warkah" and "Identitas" were automatically generated
        $this->assertDatabaseHas('drive_folders', [
            'name' => 'Warkah',
        ]);
        $this->assertDatabaseHas('drive_folders', [
            'name' => 'Identitas',
        ]);

        // File should be attached to the deepest subfolder
        $subfolder = DriveFolder::where('name', 'Identitas')->first();
        $this->assertNotNull($subfolder);
        $this->assertDatabaseHas('drive_files', [
            'name' => 'ktp_pemohon.pdf',
            'folder_id' => $subfolder->id,
        ]);
    }

    public function test_admin_can_rename_and_delete_file(): void
    {
        $file = DriveFile::create([
            'name' => 'file_lama.pdf',
            'file_path' => 'drive/test.pdf',
            'file_size' => 1024,
            'user_id' => $this->admin->id,
        ]);

        // Rename
        $renameResponse = $this->actingAs($this->admin)->put(route('admin.drive.files.update', $file->id), [
            'name' => 'file_baru.pdf',
        ]);
        $renameResponse->assertRedirect();
        $this->assertDatabaseHas('drive_files', ['name' => 'file_baru.pdf']);

        // Delete
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.drive.files.destroy', $file->id));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('drive_files', ['id' => $file->id]);
    }

    public function test_admin_can_download_folder_as_zip(): void
    {
        $folder = DriveFolder::create([
            'name' => 'Folder Arsip Download',
            'user_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('drive.folders.download', $folder->id));

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'zip'));
    }

    public function test_pimpinan_can_access_read_only_drive(): void
    {
        $response = $this->actingAs($this->pimpinan)->get(route('pimpinan.drive.index'));

        $response->assertStatus(200);
        // Pimpinan should NOT see the "+ Baru" button
        $response->assertDontSee('id="new-action-dropdown-container"', false);
    }
}
