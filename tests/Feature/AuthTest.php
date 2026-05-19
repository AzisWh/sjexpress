<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('auth.index');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin-dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_login_fails_with_nonexistent_email(): void
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_login_requires_email(): void
    {
        $response = $this->post('/login', [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_requires_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@test.com',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_login_requires_valid_email_format(): void
    {
        $response = $this->post('/login', [
            'email' => 'not-an-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect(route('login-view'));
        $this->assertGuest();
    }

    public function test_unauthenticated_user_cannot_access_admin_routes(): void
    {
        $protectedRoutes = [
            '/admin/dashboard',
            '/admin-pengiriman',
            '/admin-pt',
            '/admin-armada',
            '/admin-driver',
            '/admin-signature',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect(route('login-view'));
        }
    }

    public function test_authenticated_user_can_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_session_is_regenerated_on_login(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->get('/');
        $oldSessionId = session()->getId();

        $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $this->assertNotEquals($oldSessionId, session()->getId());
    }

    public function test_logout_invalidates_session(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post('/logout');

        $this->assertGuest();
    }

    public function test_multiple_failed_login_attempts_dont_lock_account(): void
    {
        User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'admin@test.com',
                'password' => 'wrong',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin-dashboard'));
        $this->assertAuthenticated();
    }
}
