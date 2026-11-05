<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_register_as_warga_and_must_login_manually(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'petugas',
        ]);

        $this->assertGuest();
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'warga',
        ]);
        $response->assertRedirect(route('login', absolute: false));

        $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ])->assertRedirect(route('lapor.form', absolute: false));

        $this->assertAuthenticated();
    }
}
