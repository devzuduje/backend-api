<?php

namespace App\Actions\V1\HotelRoom;

use App\Models\HotelRoom;
use App\Repositories\HotelRoom\HotelRoomRepositoryInterface;

final readonly class RestoreHotelRoomAction
{
    public function __construct(
        private HotelRoomRepositoryInterface $hotelRoomRepository
    ) {}

    public function __invoke(int $hotelRoomId): HotelRoom
    {
        return $this->hotelRoomRepository->restore($hotelRoomId);
    }
}
