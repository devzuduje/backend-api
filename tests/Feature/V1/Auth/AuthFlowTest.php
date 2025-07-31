<?php

namespace Tests\Feature\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_authentication_flow(): void
    {
        // 1. Register a new user
        $registerData = [
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $registerResponse = $this->postJson(route('api.v1.auth.register'), $registerData);

        $registerResponse->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email'],
                'token',
            ]);

        $registerToken = $registerResponse->json('token');

        // 2. Verify the user can access protected routes with registration token
        $userResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $registerToken,
        ])->getJson(route('api.v1.user.profile'));

        $userResponse->assertStatus(200)
            ->assertJsonPath('user.email', 'juan.perez@example.com');

        // 3. Logout with registration token
        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $registerToken,
        ])->postJson(route('api.v1.auth.logout'));

        $logoutResponse->assertStatus(200);

        // 4. Login with the same credentials
        $loginData = [
            'email' => 'juan.perez@example.com',
            'password' => 'password123',
        ];

        $loginResponse = $this->postJson(route('api.v1.auth.login'), $loginData);

        $loginResponse->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email'],
                'token',
            ]);

        $loginToken = $loginResponse->json('token');

        // 5. Verify the user can access protected routes with login token
        $userResponse2 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $loginToken,
        ])->getJson(route('api.v1.user.profile'));

        $userResponse2->assertStatus(200)
            ->assertJsonPath('user.email', 'juan.perez@example.com');

        // 6. Verify tokens are different
        $this->assertNotEquals($registerToken, $loginToken);

        // 7. Final logout
        $finalLogoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $loginToken,
        ])->postJson(route('api.v1.auth.logout'));

        $finalLogoutResponse->assertStatus(200);
    }

    public function test_login_with_wrong_credentials_fails(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('correct_password'),
        ]);

        $loginData = [
            'email' => 'user@example.com',
            'password' => 'wrong_password',
        ];

        $response = $this->postJson(route('api.v1.auth.login'), $loginData);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Las credenciales proporcionadas son incorrectas.',
            ]);
    }

    public function test_access_protected_route_without_token_fails(): void
    {
        $response = $this->getJson(route('api.v1.user.profile'));

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_register_with_duplicate_email_fails(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $registerData = [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('api.v1.auth.register'), $registerData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}