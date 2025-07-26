<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccommodationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accommodations = [
            [
                'name' => 'single',
                'code' => 'SGL',
                'capacity' => 1,
            ],
            [
                'name' => 'double',
                'code' => 'DBL',
                'capacity' => 2,
            ],
            [
                'name' => 'triple',
                'code' => 'TPL',
                'capacity' => 3,
            ],
            [
                'name' => 'quadruple',
                'code' => 'QDR',
                'capacity' => 4,
            ],
        ];

        foreach ($accommodations as $accommodation) {
            \App\Models\Accommodation::query()->firstOrCreate(['code' => $accommodation['code']], $accommodation);
        }
    }
}
