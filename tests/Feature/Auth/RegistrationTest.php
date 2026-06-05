<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Registrasi publik dinonaktifkan untuk website jurusan ini.
 * Akun admin dikelola via seeder/tinker.
 *
 * Test ini memverifikasi bahwa endpoint /register tidak bisa diakses.
 */
class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_is_disabled(): void
    {
        $response = $this->get('/register');

        // Route dinonaktifkan, harus 404
        $response->assertStatus(404);
    }

    public function test_registration_post_is_disabled(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(404);
    }
}
