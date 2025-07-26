<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private string $loginEndpoint = '/api/v1/auth/login';

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson($this->loginEndpoint, $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
                'token',
            ])
            ->assertJson([
                'message' => 'Inicio de sesión exitoso',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);

        // Verify token is valid
        $this->assertNotEmpty($response->json('token'));

        // Verify user can access protected routes with token
        $token = $response->json('token');
        $protectedResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/user');

        $protectedResponse->assertStatus(200);
    }

    public function test_user_cannot_login_with_invalid_email(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'wrong@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson($this->loginEndpoint, $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJson([
                'message' => 'Las credenciales proporcionadas son incorrectas.',
            ]);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'wrong_password',
        ];

        $response = $this->postJson($this->loginEndpoint, $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJson([
                'message' => 'Las credenciales proporcionadas son incorrectas.',
            ]);
    }

    public function test_user_cannot_login_with_invalid_email_format(): void
    {
        $loginData = [
            'email' => 'invalid-email',
            'password' => 'password123',
        ];

        $response = $this->postJson($this->loginEndpoint, $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_login_without_email(): void
    {
        $loginData = [
            'password' => 'password123',
        ];

        $response = $this->postJson($this->loginEndpoint, $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_login_without_password(): void
    {
        $loginData = [
            'email' => 'test@example.com',
        ];

        $response = $this->postJson($this->loginEndpoint, $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_cannot_login_with_empty_credentials(): void
    {
        $response = $this->postJson($this->loginEndpoint, []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_login_response_does_not_include_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson($this->loginEndpoint, $loginData);

        $response->assertStatus(200)
            ->assertJsonMissing(['password']);

        $responseData = $response->json();
        $this->assertArrayNotHasKey('password', $responseData['user']);
    }

    public function test_multiple_login_attempts_create_different_tokens(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $firstResponse = $this->postJson($this->loginEndpoint, $loginData);
        $secondResponse = $this->postJson($this->loginEndpoint, $loginData);

        $firstToken = $firstResponse->json('token');
        $secondToken = $secondResponse->json('token');

        $this->assertNotEquals($firstToken, $secondToken);

        // Both tokens should be valid
        $this->withHeaders(['Authorization' => 'Bearer ' . $firstToken])
            ->getJson('/api/v1/user')
            ->assertStatus(200);

        $this->withHeaders(['Authorization' => 'Bearer ' . $secondToken])
            ->getJson('/api/v1/user')
            ->assertStatus(200);
    }
}