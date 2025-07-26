<?php

namespace App\Actions\V1\Hotel;

use App\Models\Hotel;
use App\Repositories\Hotel\HotelRepositoryInterface;

final readonly class UpdateHotelAction
{
    public function __construct(
        private HotelRepositoryInterface $hotelRepository
    ) {}

    public function __invoke(Hotel $hotel, array $data): Hotel
    {
        return $this->hotelRepository->update($hotel, $data);
    }
}
