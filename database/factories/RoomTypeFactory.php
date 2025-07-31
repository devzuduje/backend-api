<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoomType>
 */
class RoomTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['Standard', 'Deluxe', 'Suite', 'Premium', 'Executive'];
        $name = $this->faker->randomElement($types);
        
        return [
            'name' => $name . ' ' . $this->faker->word(),
            'code' => strtoupper($this->faker->unique()->lexify('???')),
        ];
    }
}
