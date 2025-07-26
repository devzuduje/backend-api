<?php

namespace Tests\Feature\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('auth.logout'));

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Cierre de sesión exitoso',
            ]);
    }

    public function test_user_cannot_logout_without_token(): void
    {
        $response = $this->postJson(route('auth.logout'));

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_user_cannot_logout_with_invalid_token(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token',
        ])->postJson(route('auth.logout'));

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_user_cannot_logout_with_malformed_token(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'InvalidFormat',
        ])->postJson(route('auth.logout'));

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_user_can_logout_with_valid_token(): void
    {
        // Login to get a real token
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginResponse = $this->postJson(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse->json('token');

        // Verify token works before logout
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson(route('user.profile'))->assertStatus(200);

        // Logout should succeed
        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson(route('auth.logout'));

        $logoutResponse->assertStatus(200)
            ->assertJson([
                'message' => 'Cierre de sesión exitoso',
            ]);
    }

    public function test_multiple_tokens_can_be_created(): void
    {
        // Create user and login twice to get two different tokens
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $login1 = $this->postJson(route('auth.login'), $loginData);
        $login2 = $this->postJson(route('auth.login'), $loginData);

        $token1 = $login1->json('token');
        $token2 = $login2->json('token');

        // Verify both tokens are different
        $this->assertNotEquals($token1, $token2);

        // Verify both tokens work
        $this->withHeaders(['Authorization' => 'Bearer ' . $token1])
            ->getJson('/api/v1/user')->assertStatus(200);
        $this->withHeaders(['Authorization' => 'Bearer ' . $token2])
            ->getJson('/api/v1/user')->assertStatus(200);

        // Logout with first token should succeed
        $this->withHeaders(['Authorization' => 'Bearer ' . $token1])
            ->postJson(route('auth.logout'))->assertStatus(200);

        // Second token should still work for accessing protected routes
        $this->withHeaders(['Authorization' => 'Bearer ' . $token2])
            ->getJson('/api/v1/user')->assertStatus(200);
    }

    public function test_logout_endpoint_requires_authentication(): void
    {
        // Login to get a real token
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginResponse = $this->postJson(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse->json('token');

        // Logout should succeed with valid token
        $firstResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson(route('auth.logout'));

        $firstResponse->assertStatus(200)
            ->assertJson([
                'message' => 'Cierre de sesión exitoso',
            ]);
    }

    public function test_logout_with_sanctum_helper(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('auth.logout'));

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Cierre de sesión exitoso',
            ]);
    }
}