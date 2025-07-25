<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            RoomType::firstOrCreate(
                ['code' => $roomType['code']], 
                $roomType
            );
        }
    }
}
