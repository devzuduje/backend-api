<?php

namespace Tests\Feature\V1\HotelRoom;

use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\RoomType;
use App\Models\Accommodation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelRoomTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_create_hotel_room_with_valid_data(): void
    {
        $hotel = Hotel::factory()->create(['max_rooms' => 100]);
        $roomType = RoomType::factory()->create();
        $accommodation = Accommodation::factory()->create();

        $hotelRoomData = [
            'hotel_id' => $hotel->id,
            'room_type_id' => $roomType->id,
            'accommodation_id' => $accommodation->id,
            'quantity' => 10,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.hotel-rooms.store'), $hotelRoomData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'hotel_room' => ['id', 'hotel_id', 'room_type_id', 'accommodation_id', 'quantity'],
            ])
            ->assertJsonPath('hotel_room.hotel_id', $hotel->id)
            ->assertJsonPath('hotel_room.room_type_id', $roomType->id)
            ->assertJsonPath('hotel_room.accommodation_id', $accommodation->id)
            ->assertJsonPath('hotel_room.quantity', 10);

        $this->assertDatabaseHas('hotel_rooms', [
            'hotel_id' => $hotel->id,
            'room_type_id' => $roomType->id,
            'accommodation_id' => $accommodation->id,
            'quantity' => 10,
        ]);
    }

    public function test_cannot_create_hotel_room_with_duplicate_combination(): void
    {
        $hotel = Hotel::factory()->create(['max_rooms' => 100]);
        $roomType = RoomType::factory()->create();
        $accommodation = Accommodation::factory()->create();

        // Crear la primera habitación
        HotelRoom::factory()->create([
            'hotel_id' => $hotel->id,
            'room_type_id' => $roomType->id,
            'accommodation_id' => $accommodation->id,
        ]);

        // Intentar crear otra con la misma combinación
        $hotelRoomData = [
            'hotel_id' => $hotel->id,
            'room_type_id' => $roomType->id,
            'accommodation_id' => $accommodation->id,
            'quantity' => 5,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.hotel-rooms.store'), $hotelRoomData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['combination'])
            ->assertJsonPath('errors.combination.0', 'Ya existe una habitación con esta combinación de hotel, tipo de habitación y acomodación.');
    }

    public function test_cannot_create_hotel_room_exceeding_hotel_max_rooms(): void
    {
        $hotel = Hotel::factory()->create(['max_rooms' => 50]);
        $roomType = RoomType::factory()->create();
        $accommodation = Accommodation::factory()->create();

        // Crear habitaciones existentes que sumen 40
        HotelRoom::factory()->create([
            'hotel_id' => $hotel->id,
            'quantity' => 40,
        ]);

        // Intentar agregar 20 más (excedería el límite de 50)
        $hotelRoomData = [
            'hotel_id' => $hotel->id,
            'room_type_id' => $roomType->id,
            'accommodation_id' => $accommodation->id,
            'quantity' => 20,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.hotel-rooms.store'), $hotelRoomData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['quantity'])
            ->assertJsonPath('errors.quantity.0', 'No se pueden agregar 20 habitaciones. El hotel permite máximo 50 habitaciones. Ya hay 40 registradas. Disponibles: 10.');
    }

    public function test_can_update_hotel_room_quantity(): void
    {
        $hotel = Hotel::factory()->create(['max_rooms' => 100]);
        $hotelRoom = HotelRoom::factory()->create([
            'hotel_id' => $hotel->id,
            'quantity' => 10,
        ]);

        $updateData = ['quantity' => 15];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson(route('api.v1.hotel-rooms.update', $hotelRoom->id), $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('hotel_room.quantity', 15);

        $this->assertDatabaseHas('hotel_rooms', [
            'id' => $hotelRoom->id,
            'quantity' => 15,
        ]);
    }

    public function test_cannot_update_hotel_room_exceeding_max_rooms(): void
    {
        $hotel = Hotel::factory()->create(['max_rooms' => 50]);
        
        // Crear otras habitaciones que sumen 30
        HotelRoom::factory()->create([
            'hotel_id' => $hotel->id,
            'quantity' => 30,
        ]);

        // Crear la habitación a actualizar con 10
        $hotelRoom = HotelRoom::factory()->create([
            'hotel_id' => $hotel->id,
            'quantity' => 10,
        ]);

        // Intentar actualizar a 25 (30 + 25 = 55 > 50)
        $updateData = ['quantity' => 25];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson(route('api.v1.hotel-rooms.update', $hotelRoom->id), $updateData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['quantity'])
            ->assertJsonPath('errors.quantity.0', 'No se puede actualizar a 25 habitaciones. El hotel permite máximo 50 habitaciones. Ya hay 30 registradas (excluyendo esta). Disponibles: 20.');
    }

    public function test_can_get_hotel_room_details(): void
    {
        $hotelRoom = HotelRoom::factory()->create(['quantity' => 8]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.v1.hotel-rooms.show', $hotelRoom->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'hotel_room' => ['id', 'hotel_id', 'room_type_id', 'accommodation_id', 'quantity'],
            ])
            ->assertJsonPath('hotel_room.quantity', 8);
    }

    public function test_can_list_hotel_rooms(): void
    {
        HotelRoom::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.v1.hotel-rooms.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'hotel_rooms' => [
                    '*' => ['id', 'hotel_id', 'room_type_id', 'accommodation_id', 'quantity']
                ]
            ])
            ->assertJsonCount(3, 'hotel_rooms');
    }

    public function test_can_soft_delete_hotel_room(): void
    {
        $hotelRoom = HotelRoom::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson(route('api.v1.hotel-rooms.destroy', $hotelRoom->id));

        $response->assertStatus(200);

        $this->assertSoftDeleted('hotel_rooms', ['id' => $hotelRoom->id]);
    }

    public function test_can_restore_soft_deleted_hotel_room(): void
    {
        $hotelRoom = HotelRoom::factory()->create();
        $hotelRoom->delete();

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/hotel-rooms/' . $hotelRoom->id . '/restore');

        $response->assertStatus(200);

        $this->assertDatabaseHas('hotel_rooms', [
            'id' => $hotelRoom->id,
            'deleted_at' => null,
        ]);
    }

    public function test_cannot_get_nonexistent_hotel_room(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.v1.hotel-rooms.show', 999));

        $response->assertStatus(404);
    }
}