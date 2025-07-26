<?php

namespace App\Repositories\HotelRoom;

use App\Models\HotelRoom;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface HotelRoomRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function findById(int $hotelRoomId): ?HotelRoom;

    public function create(array $data): HotelRoom;

    public function update(HotelRoom $hotelRoom, array $data): HotelRoom;

    public function delete(HotelRoom $hotelRoom): bool;

    public function restore(int $hotelRoomId): HotelRoom;

    public function findTrashed(int $hotelRoomId): ?HotelRoom;
}
