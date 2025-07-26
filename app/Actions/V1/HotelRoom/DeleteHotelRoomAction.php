<?php

namespace App\Actions\V1\HotelRoom;

use App\Models\HotelRoom;
use App\Repositories\HotelRoom\HotelRoomRepositoryInterface;

final readonly class DeleteHotelRoomAction
{
    public function __construct(
        private HotelRoomRepositoryInterface $hotelRoomRepository
    ) {}

    public function __invoke(HotelRoom $hotelRoom): bool
    {
        return $this->hotelRoomRepository->delete($hotelRoom);
    }
}
