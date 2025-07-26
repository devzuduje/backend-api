<?php

namespace App\Actions\V1\HotelRoom;

use App\Repositories\HotelRoom\HotelRoomRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class GetHotelRoomsAction
{
    public function __construct(
        private HotelRoomRepositoryInterface $hotelRoomRepository
    ) {}

    public function __invoke(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->hotelRoomRepository->getAllPaginated($filters, $perPage);
    }
}
