<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Accommodation>
 */
class AccommodationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $accommodations = ['Sencilla', 'Doble', 'Triple', 'Cuádruple', 'Familiar'];
        $name = $this->faker->randomElement($accommodations);
        
        return [
            'name' => 'Acomodación ' . $name,
            'code' => strtoupper($this->faker->unique()->lexify('????')),
            'capacity' => $this->faker->numberBetween(1, 8),
        ];
    }
}
