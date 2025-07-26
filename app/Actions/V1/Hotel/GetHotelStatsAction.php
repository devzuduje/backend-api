<?php

namespace App\Actions\V1\Hotel;

use App\Repositories\Hotel\HotelRepositoryInterface;

final readonly class GetHotelStatsAction
{
    public function __construct(
        private HotelRepositoryInterface $hotelRepository
    ) {}

    public function __invoke(): array
    {
        $totalHotels = $this->hotelRepository->getPaginated()->total();
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Hotel> $topHotels */
        $topHotels = $this->hotelRepository->getTopHotelsByRooms(5);

        $citiesCount = $this->hotelRepository->getWithRelations([])
            ->groupBy('city')
            ->map(fn ($hotels) => $hotels->count())
            ->sortDesc()
            ->take(10);

        return [
            'total_hotels' => $totalHotels,
            'top_hotels_by_rooms' => $topHotels->map(fn (\App\Models\Hotel $hotel) => [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'city' => $hotel->city,
                'max_rooms' => $hotel->max_rooms,
            ]),
            'hotels_by_city' => $citiesCount->map(fn ($count, $city) => [
                'city' => $city,
                'hotels_count' => $count,
            ])->values(),
        ];
    }
}
