<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $pimpinan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Administrator Arsip',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->pimpinan = User::create([
            'name' => 'Kepala Kantor',
            'email' => 'pimpinan@test.com',
            'password' => Hash::make('password123'),
            'role' => 'pimpinan',
        ]);
    }

    public function test_guest_cannot_access_profile_pages(): void
    {
        $this->get('/admin/profile')->assertRedirect('/admin');
        $this->get('/pimpinan/profile')->assertRedirect('/admin');
        $this->get('/profile')->assertRedirect('/admin');
    }

    public function test_admin_can_access_admin_profile_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/profile');

        $response->assertStatus(200);
        $response->assertSee('Profil &amp; Keamanan Akun', false);
        $response->assertSee('Administrator Arsip');
        $response->assertSee($this->admin->email);
    }

    public function test_pimpinan_can_access_pimpinan_profile_page(): void
    {
        $response = $this->actingAs($this->pimpinan)->get('/pimpinan/profile');

        $response->assertStatus(200);
        $response->assertSee('Profil &amp; Keamanan Akun', false);
        $response->assertSee('Pimpinan (Read-Only)');
        $response->assertSee($this->pimpinan->email);
    }

    public function test_generic_profile_route_redirects_based_on_role(): void
    {
        $adminResponse = $this->actingAs($this->admin)->get('/profile');
        $adminResponse->assertRedirect(route('admin.profile.edit'));

        $pimpinanResponse = $this->actingAs($this->pimpinan)->get('/profile');
        $pimpinanResponse->assertRedirect(route('pimpinan.profile.edit'));
    }

    public function test_admin_cannot_access_pimpinan_profile(): void
    {
        $response = $this->actingAs($this->admin)->get('/pimpinan/profile');
        $response->assertStatus(403);
    }

    public function test_pimpinan_cannot_access_admin_profile(): void
    {
        $response = $this->actingAs($this->pimpinan)->get('/admin/profile');
        $response->assertStatus(403);
    }

    public function test_admin_can_update_profile_information(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/profile', [
            'name' => 'Nama Baru Admin',
            'email' => 'admin_baru@test.com',
        ]);

        $response->assertSessionHas('success', 'Informasi profil berhasil diperbarui.');
        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'name' => 'Nama Baru Admin',
            'email' => 'admin_baru@test.com',
        ]);
    }

    public function test_pimpinan_can_update_profile_information(): void
    {
        $response = $this->actingAs($this->pimpinan)->put('/pimpinan/profile', [
            'name' => 'Nama Baru Pimpinan',
            'email' => 'pimpinan_baru@test.com',
        ]);

        $response->assertSessionHas('success', 'Informasi profil berhasil diperbarui.');
        $this->assertDatabaseHas('users', [
            'id' => $this->pimpinan->id,
            'name' => 'Nama Baru Pimpinan',
            'email' => 'pimpinan_baru@test.com',
        ]);
    }

    public function test_user_can_keep_their_current_email_when_updating_profile(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/profile', [
            'name' => 'Admin Tetap',
            'email' => $this->admin->email,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'name' => 'Admin Tetap',
            'email' => $this->admin->email,
        ]);
    }

    public function test_profile_update_validation_fails_with_duplicate_email(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/profile', [
            'name' => 'Admin Duplikat',
            'email' => $this->pimpinan->email, // Email already used by pimpinan
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_admin_can_update_password_with_correct_current_password(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/profile/password', [
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHas('success', 'Password akun berhasil diperbarui.');

        $this->admin->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->admin->password));
    }

    public function test_pimpinan_can_update_password_with_correct_current_password(): void
    {
        $response = $this->actingAs($this->pimpinan)->put('/pimpinan/profile/password', [
            'current_password' => 'password123',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertSessionHas('success', 'Password akun berhasil diperbarui.');

        $this->pimpinan->refresh();
        $this->assertTrue(Hash::check('newpassword456', $this->pimpinan->password));
    }

    public function test_password_update_fails_if_current_password_is_incorrect(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/profile/password', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');

        $this->admin->refresh();
        $this->assertTrue(Hash::check('password123', $this->admin->password));
    }

    public function test_password_update_fails_if_password_confirmation_does_not_match(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/profile/password', [
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_password_update_fails_if_password_is_too_short(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/profile/password', [
            'current_password' => 'password123',
            'password' => '12345',
            'password_confirmation' => '12345',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
