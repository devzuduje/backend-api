<?php

namespace App\Actions\V1\Hotel;

use App\Models\Hotel;
use App\Repositories\Hotel\HotelRepositoryInterface;

final readonly class DeleteHotelAction
{
    public function __construct(
        private HotelRepositoryInterface $hotelRepository
    ) {}

    public function __invoke(Hotel $hotel): bool
    {
        return $this->hotelRepository->delete($hotel);
    }
}
