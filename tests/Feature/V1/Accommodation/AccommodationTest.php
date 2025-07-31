<?php

namespace Tests\Feature\V1\Accommodation;

use App\Models\Accommodation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccommodationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_create_accommodation_with_valid_data(): void
    {
        $accommodationData = [
            'name' => 'Acomodación Sencilla',
            'code' => 'SIMPLE',
            'capacity' => 2,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.accommodations.store'), $accommodationData);

        $response->assertStatus(201);
        
        // Debug: Ver la estructura real de la respuesta
        dump($response->json());

        $this->assertDatabaseHas('accommodations', [
            'name' => 'Acomodación Sencilla',
            'code' => 'SIMPLE',
            'capacity' => 2,
        ]);
    }

    public function test_cannot_create_accommodation_with_duplicate_name(): void
    {
        Accommodation::factory()->create(['name' => 'Acomodación Existente']);

        $accommodationData = [
            'name' => 'Acomodación Existente',
            'code' => 'NUEVA',
            'capacity' => 3,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.accommodations.store'), $accommodationData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonPath('errors.name.0', 'Ya existe una acomodación con este nombre.');
    }

    public function test_cannot_create_accommodation_with_duplicate_code(): void
    {
        Accommodation::factory()->create(['code' => 'DOUBLE']);

        $accommodationData = [
            'name' => 'Acomodación Nueva',
            'code' => 'DOUBLE',
            'capacity' => 4,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.accommodations.store'), $accommodationData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code'])
            ->assertJsonPath('errors.code.0', 'Ya existe una acomodación con este código.');
    }

    public function test_cannot_create_accommodation_without_required_fields(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson(route('api.v1.accommodations.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'code', 'capacity']);
    }

    public function test_can_update_accommodation_with_valid_data(): void
    {
        $accommodation = Accommodation::factory()->create([
            'name' => 'Acomodación Original',
            'code' => 'ORIGINAL',
            'capacity' => 2,
        ]);

        $updateData = [
            'name' => 'Acomodación Actualizada',
            'code' => 'UPDATED',
            'capacity' => 4,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson(route('api.v1.accommodations.update', $accommodation->id), $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('accommodation.name', 'Acomodación Actualizada')
            ->assertJsonPath('accommodation.code', 'UPDATED')
            ->assertJsonPath('accommodation.capacity', 4);

        $this->assertDatabaseHas('accommodations', [
            'id' => $accommodation->id,
            'name' => 'Acomodación Actualizada',
            'code' => 'UPDATED',
            'capacity' => 4,
        ]);
    }

    public function test_can_get_accommodation_details(): void
    {
        $accommodation = Accommodation::factory()->create([
            'name' => 'Suite Familiar',
            'code' => 'FAMILY',
            'capacity' => 6,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.v1.accommodations.show', $accommodation->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'accommodation' => ['id', 'name', 'code', 'capacity'],
            ])
            ->assertJsonPath('accommodation.name', 'Suite Familiar')
            ->assertJsonPath('accommodation.code', 'FAMILY')
            ->assertJsonPath('accommodation.capacity', 6);
    }

    public function test_can_list_accommodations(): void
    {
        Accommodation::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.v1.accommodations.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'accommodations' => [
                    '*' => ['id', 'name', 'code', 'capacity']
                ]
            ])
            ->assertJsonCount(3, 'accommodations');
    }

    public function test_can_soft_delete_accommodation(): void
    {
        $accommodation = Accommodation::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson(route('api.v1.accommodations.destroy', $accommodation->id));

        $response->assertStatus(200);

        $this->assertSoftDeleted('accommodations', ['id' => $accommodation->id]);
    }

    public function test_can_restore_soft_deleted_accommodation(): void
    {
        $accommodation = Accommodation::factory()->create();
        $accommodation->delete();

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/accommodations/' . $accommodation->id . '/restore');

        $response->assertStatus(200);

        $this->assertDatabaseHas('accommodations', [
            'id' => $accommodation->id,
            'deleted_at' => null,
        ]);
    }

    public function test_cannot_get_nonexistent_accommodation(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.v1.accommodations.show', 999));

        $response->assertStatus(404);
    }
}