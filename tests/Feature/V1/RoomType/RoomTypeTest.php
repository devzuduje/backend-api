<?php

namespace Tests\Feature\V1\RoomType;

use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomTypeTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_create_room_type_with_valid_data(): void
    {
        $roomTypeData = [
            'name' => 'Suite Presidencial',
            'code' => 'SUITE_PRES',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.room-types.store'), $roomTypeData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'room_type' => ['id', 'name', 'code'],
            ])
            ->assertJsonPath('room_type.name', 'Suite Presidencial')
            ->assertJsonPath('room_type.code', 'SUITE_PRES');

        $this->assertDatabaseHas('room_types', [
            'name' => 'Suite Presidencial',
            'code' => 'SUITE_PRES',
        ]);
    }

    public function test_cannot_create_room_type_with_duplicate_code(): void
    {
        RoomType::factory()->create(['code' => 'SINGLE']);

        $roomTypeData = [
            'name' => 'Habitación Individual Nueva',
            'code' => 'SINGLE',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.room-types.store'), $roomTypeData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code'])
            ->assertJsonPath('errors.code.0', 'Ya existe un tipo de habitación con este código.');
    }

    public function test_cannot_create_room_type_without_required_fields(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.room-types.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'code']);
    }

    public function test_can_update_room_type_with_valid_data(): void
    {
        $roomType = RoomType::factory()->create([
            'name' => 'Habitación Original',
            'code' => 'ORIGINAL',
        ]);

        $updateData = [
            'name' => 'Habitación Actualizada',
            'code' => 'UPDATED',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson(route('api.v1.room-types.update', $roomType->id), $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('room_type.name', 'Habitación Actualizada')
            ->assertJsonPath('room_type.code', 'UPDATED');

        $this->assertDatabaseHas('room_types', [
            'id' => $roomType->id,
            'name' => 'Habitación Actualizada',
            'code' => 'UPDATED',
        ]);
    }

    public function test_can_get_room_type_details(): void
    {
        $roomType = RoomType::factory()->create([
            'name' => 'Habitación Deluxe',
            'code' => 'DELUXE',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.v1.room-types.show', $roomType->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'room_type' => ['id', 'name', 'code'],
            ])
            ->assertJsonPath('room_type.name', 'Habitación Deluxe')
            ->assertJsonPath('room_type.code', 'DELUXE');
    }

    public function test_can_list_room_types(): void
    {
        RoomType::factory()->count(4)->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.v1.room-types.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'room_types' => [
                    '*' => ['id', 'name', 'code']
                ]
            ])
            ->assertJsonCount(4, 'room_types');
    }

    public function test_can_soft_delete_room_type(): void
    {
        $roomType = RoomType::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson(route('api.v1.room-types.destroy', $roomType->id));

        $response->assertStatus(200);

        $this->assertSoftDeleted('room_types', ['id' => $roomType->id]);
    }

    public function test_can_restore_soft_deleted_room_type(): void
    {
        $roomType = RoomType::factory()->create();
        $roomType->delete();

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/room-types/' . $roomType->id . '/restore');

        $response->assertStatus(200);

        $this->assertDatabaseHas('room_types', [
            'id' => $roomType->id,
            'deleted_at' => null,
        ]);
    }

    public function test_cannot_get_nonexistent_room_type(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.v1.room-types.show', 999));

        $response->assertStatus(404);
    }

    public function test_cannot_update_nonexistent_room_type(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson(route('api.v1.room-types.update', 999), [
            'name' => 'Inexistente',
        ]);

        $response->assertStatus(404);
    }

    public function test_cannot_delete_nonexistent_room_type(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson(route('api.v1.room-types.destroy', 999));

        $response->assertStatus(404);
    }
}