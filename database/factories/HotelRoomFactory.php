<?php

namespace Database\Factories;

use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\Accommodation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HotelRoom>
 */
class HotelRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hotel_id' => Hotel::factory(),
            'room_type_id' => RoomType::factory(),
            'accommodation_id' => Accommodation::factory(),
            'quantity' => $this->faker->numberBetween(1, 20),
        ];
    }
}
