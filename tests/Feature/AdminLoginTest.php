<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_with_modern_elements(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('guest-wrapper', false);
        $response->assertSee('guest-card', false);
        $response->assertSee('guest-top-bar', false);
        $response->assertSee('guest-brand-logo', false);
        $response->assertSee('togglePasswordBtn', false);
        $response->assertSee('name="login"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="remember"', false);
        $response->assertSee('Password or Portal Access Code', false);
    }

    public function test_user_can_authenticate_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'operator@example.com',
            'password' => bcrypt('secret1234'),
            'status' => 'active',
        ]);

        $response = $this->post(route('login.post'), [
            'login' => 'operator@example.com',
            'password' => 'secret1234',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_authenticate_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'operator@example.com',
            'password' => bcrypt('correct-password'),
            'status' => 'active',
        ]);

        $response = $this->from(route('login'))->post(route('login.post'), [
            'login' => 'operator@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_authenticated_user_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create(['status' => 'active']);

        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_client_can_authenticate_on_unified_login_using_email_and_portal_access_code(): void
    {
        $client = Client::create([
            'name' => 'Unified Client',
            'email' => 'client@unified.test',
            'access_code' => 'PORTAL-4567',
            'status' => 'active',
        ]);

        $response = $this->post(route('login.post'), [
            'login' => 'client@unified.test',
            'password' => 'PORTAL-4567',
        ]);

        $response->assertRedirect(route('client.dashboard'));
        $this->assertAuthenticatedAs($client, 'client');

        $this->actingAs($client, 'client')
            ->get(route('login'))
            ->assertRedirect(route('client.dashboard'));
    }
}
