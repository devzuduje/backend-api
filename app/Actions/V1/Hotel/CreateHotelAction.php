<?php

namespace App\Actions\V1\Hotel;

use App\Models\Hotel;
use App\Repositories\Hotel\HotelRepositoryInterface;

final readonly class CreateHotelAction
{
    public function __construct(
        private HotelRepositoryInterface $hotelRepository
    ) {}

    public function __invoke(array $data): Hotel
    {
        return $this->hotelRepository->create($data);
    }
}
