<?php

namespace App\Actions\V1\Hotel;

use App\Models\Hotel;
use App\Repositories\Hotel\HotelRepositoryInterface;

final readonly class RestoreHotelAction
{
    public function __construct(
        private HotelRepositoryInterface $hotelRepository
    ) {}

    public function __invoke(int $hotelId): Hotel
    {
        return $this->hotelRepository->restore($hotelId);
    }
}
