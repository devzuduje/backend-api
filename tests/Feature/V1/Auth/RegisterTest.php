<?php

namespace Tests\Feature\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data(): void
    {
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'token',
            ])
            ->assertJson([
                'message' => 'Usuario registrado exitosamente',
                'user' => [
                    'name' => 'Juan Pérez',
                    'email' => 'juan.perez@example.com',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
        ]);

        // Verify password is hashed
        $user = User::where('email', 'juan.perez@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));

        // Verify token is valid
        $this->assertNotEmpty($response->json('token'));
    }


    public function test_user_cannot_register_with_existing_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_register_with_short_password(): void
    {
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'password' => '123',
            'password_confirmation' => '123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_cannot_register_with_mismatched_password_confirmation(): void
    {
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_password',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_cannot_register_without_required_fields(): void
    {
        $response = $this->postJson(route('auth.register'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'password_confirmation']);
    }

    public function test_user_cannot_register_with_name_too_long(): void
    {
        $userData = [
            'name' => str_repeat('a', 256), // Exceeds 255 character limit
            'email' => 'juan.perez@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_user_cannot_register_with_email_too_long(): void
    {
        $longEmail = str_repeat('a', 250) . '@example.com'; // Exceeds 255 character limit

        $userData = [
            'name' => 'Juan Pérez',
            'email' => $longEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_registration_response_does_not_include_password(): void
    {
        $userData = [
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('auth.register'), $userData);

        $response->assertStatus(201)
            ->assertJsonMissing(['password']);

        $responseData = $response->json();
        $this->assertArrayNotHasKey('password', $responseData['user']);
    }
}