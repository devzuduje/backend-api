<?php

namespace Tests\Feature\V1\Hotel;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_create_hotel_with_valid_data(): void
    {
        $hotelData = [
            'name' => 'Hotel Plaza Mayor',
            'address' => 'Calle 10 # 15-20, Centro Histórico',
            'city' => 'Bogotá',
            'nit' => '900123456-1',
            'email' => 'contacto@hotelplazamayor.com',
            'phone' => '+57 1 234 5678',
            'max_rooms' => 150,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.hotels.store'), $hotelData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('hotels', [
            'name' => 'HOTEL PLAZA MAYOR',
            'city' => 'Bogotá',
            'nit' => '900123456-1',
            'email' => 'contacto@hotelplazamayor.com',
            'phone' => '+57 1 234 5678',
        ]);
    }

    public function test_cannot_create_hotel_with_duplicate_name_in_same_city(): void
    {
        Hotel::factory()->create([
            'name' => 'Hotel Central',
            'city' => 'Medellín',
        ]);

        $hotelData = [
            'name' => 'Hotel Central',
            'address' => 'Calle 50 # 25-30',
            'city' => 'Medellín',
            'nit' => '900123456-2',
            'email' => 'contacto@hotelcentral.com',
            'phone' => '+57 4 123 4567',
            'max_rooms' => 100,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.hotels.store'), $hotelData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonPath('errors.name.0', 'Ya existe un hotel con este nombre en la misma ciudad.');
    }

    public function test_can_create_hotel_with_same_name_in_different_city(): void
    {
        Hotel::factory()->create([
            'name' => 'Hotel Central',
            'city' => 'Medellín',
        ]);

        $hotelData = [
            'name' => 'Hotel Central',
            'address' => 'Carrera 7 # 72-41',
            'city' => 'Bogotá',
            'nit' => '900123456-3',
            'email' => 'bogota@hotelcentral.com',
            'phone' => '+57 1 987 6543',
            'max_rooms' => 120,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.hotels.store'), $hotelData);

        $response->assertStatus(201)
            ->assertJsonPath('hotel.name', 'Hotel Central')
            ->assertJsonPath('hotel.city', 'Bogotá');

        $this->assertDatabaseHas('hotels', [
            'name' => 'Hotel Central',
            'city' => 'Bogotá',
        ]);
    }

    public function test_cannot_create_hotel_with_duplicate_nit(): void
    {
        Hotel::factory()->create(['nit' => '900123456-1']);

        $hotelData = [
            'name' => 'Hotel Nuevo',
            'address' => 'Calle 85 # 15-20',
            'city' => 'Cali',
            'nit' => '900123456-1',
            'email' => 'nuevo@hotel.com',
            'phone' => '+57 2 345 6789',
            'max_rooms' => 80,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.hotels.store'), $hotelData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nit'])
            ->assertJsonPath('errors.nit.0', 'Ya existe un hotel con este NIT.');
    }

    public function test_cannot_create_hotel_with_duplicate_email(): void
    {
        Hotel::factory()->create(['email' => 'contacto@hotel.com']);

        $hotelData = [
            'name' => 'Hotel Nuevo',
            'address' => 'Calle 85 # 15-20',
            'city' => 'Cali',
            'nit' => '900123456-9',
            'email' => 'contacto@hotel.com',
            'phone' => '+57 2 345 6789',
            'max_rooms' => 80,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.hotels.store'), $hotelData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJsonPath('errors.email.0', 'Ya existe un hotel con este email.');
    }

    public function test_cannot_create_hotel_without_required_fields(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.hotels.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'address', 'city', 'nit', 'email', 'phone', 'max_rooms']);
    }

    public function test_cannot_create_hotel_with_invalid_max_rooms(): void
    {
        $hotelData = [
            'name' => 'Hotel Test',
            'address' => 'Test Address',
            'city' => 'Test City',
            'nit' => '900123456-9',
            'max_rooms' => 0,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.hotels.store'), $hotelData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['max_rooms'])
            ->assertJsonPath('errors.max_rooms.0', 'El hotel debe tener al menos 1 habitación.');
    }

    public function test_cannot_create_hotel_with_max_rooms_exceeding_limit(): void
    {
        $hotelData = [
            'name' => 'Hotel Mega',
            'address' => 'Super Address',
            'city' => 'Mega City',
            'nit' => '900123456-8',
            'max_rooms' => 15000,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.hotels.store'), $hotelData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['max_rooms'])
            ->assertJsonPath('errors.max_rooms.0', 'El número máximo de habitaciones no puede exceder 10,000.');
    }

    public function test_can_update_hotel_with_valid_data(): void
    {
        $hotel = Hotel::factory()->create([
            'name' => 'Hotel Original',
            'city' => 'Bogotá',
            'nit' => '900123456-5',
        ]);

        $updateData = [
            'name' => 'Hotel Actualizado',
            'address' => 'Nueva dirección 123',
            'email' => 'nuevo@hotelactualizado.com',
            'phone' => '+57 1 987 6543',
            'max_rooms' => 200,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson(route('api.v1.hotels.update', $hotel->id), $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('hotels', [
            'id' => $hotel->id,
            'name' => 'HOTEL ACTUALIZADO',
            'email' => 'nuevo@hotelactualizado.com',
            'phone' => '+57 1 987 6543',
            'max_rooms' => 200,
        ]);
    }

    public function test_can_update_hotel_nit_to_unique_value(): void
    {
        $hotel = Hotel::factory()->create(['nit' => '900123456-6']);

        $updateData = ['nit' => '900123456-7'];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson(route('api.v1.hotels.update', $hotel->id), $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('hotel.nit', '900123456-7');
    }

    public function test_cannot_update_hotel_nit_to_existing_value(): void
    {
        Hotel::factory()->create(['nit' => '900123456-10']);
        $hotel = Hotel::factory()->create(['nit' => '900123456-11']);

        $updateData = ['nit' => '900123456-10'];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson(route('api.v1.hotels.update', $hotel->id), $updateData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nit']);
    }

    public function test_can_get_hotel_details(): void
    {
        $hotel = Hotel::factory()->create([
            'name' => 'Hotel Vista',
            'city' => 'Cartagena',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.v1.hotels.show', $hotel->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'hotel' => ['id', 'name', 'address', 'city', 'nit', 'max_rooms'],
            ])
            ->assertJsonPath('hotel.name', 'Hotel Vista')
            ->assertJsonPath('hotel.city', 'Cartagena');
    }

    public function test_can_list_hotels(): void
    {
        Hotel::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.v1.hotels.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'hotels' => [
                    '*' => ['id', 'name', 'address', 'city', 'nit', 'max_rooms']
                ]
            ])
            ->assertJsonCount(3, 'hotels');
    }

    public function test_can_soft_delete_hotel(): void
    {
        $hotel = Hotel::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson(route('api.v1.hotels.destroy', $hotel->id));

        $response->assertStatus(200);

        $this->assertSoftDeleted('hotels', ['id' => $hotel->id]);
    }

    public function test_can_restore_soft_deleted_hotel(): void
    {
        $hotel = Hotel::factory()->create();
        $hotel->delete();

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/hotels/' . $hotel->id . '/restore');

        $response->assertStatus(200);

        $this->assertDatabaseHas('hotels', [
            'id' => $hotel->id,
            'deleted_at' => null,
        ]);
    }

    public function test_cannot_get_nonexistent_hotel(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.v1.hotels.show', 999));

        $response->assertStatus(404);
    }

    public function test_cannot_update_nonexistent_hotel(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson(route('api.v1.hotels.update', 999), [
            'name' => 'Hotel Inexistente',
        ]);

        $response->assertStatus(404);
    }

    public function test_cannot_delete_nonexistent_hotel(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson(route('api.v1.hotels.destroy', 999));

        $response->assertStatus(404);
    }
}