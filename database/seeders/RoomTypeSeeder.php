<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = [
            [
                'name' => 'standard',
                'code' => 'STD',
            ],
            [
                'name' => 'junior',
                'code' => 'JNR',
            ],
            [
                'name' => 'suite',
                'code' => 'STE',
            ],
        ];

        foreach ($roomTypes as $roomType) {
            \App\Models\RoomType::query()->firstOrCreate(['code' => $roomType['code']], $roomType);
        }
    }
}
