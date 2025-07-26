<?php

namespace App\Actions\V1\HotelRoom;

use App\Models\HotelRoom;
use App\Repositories\HotelRoom\HotelRoomRepositoryInterface;

final readonly class CreateHotelRoomAction
{
    public function __construct(
        private HotelRoomRepositoryInterface $hotelRoomRepository
    ) {}

    public function __invoke(array $data): HotelRoom
    {
        return $this->hotelRoomRepository->create($data);
    }
}
