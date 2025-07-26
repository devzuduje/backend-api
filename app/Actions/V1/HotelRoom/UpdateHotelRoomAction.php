<?php

namespace App\Actions\V1\HotelRoom;

use App\Models\HotelRoom;
use App\Repositories\HotelRoom\HotelRoomRepositoryInterface;

final readonly class UpdateHotelRoomAction
{
    public function __construct(
        private HotelRoomRepositoryInterface $hotelRoomRepository
    ) {}

    public function __invoke(HotelRoom $hotelRoom, array $data): HotelRoom
    {
        return $this->hotelRoomRepository->update($hotelRoom, $data);
    }
}
