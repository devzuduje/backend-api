<?php

namespace App\Repositories\Hotel;

use App\Models\Hotel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface HotelRepositoryInterface
{
    public function findById(int $id): ?Hotel;

    public function findByNit(string $nit): ?Hotel;

    public function findByCity(string $city): Collection;

    public function findWithMinRooms(int $minRooms): Collection;

    public function search(string $term): Collection;

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function getWithRelations(array $relations = []): Collection;

    public function create(array $data): Hotel;

    public function update(Hotel $hotel, array $data): Hotel;

    public function delete(Hotel $hotel): bool;

    public function getTopHotelsByRooms(int $limit = 10): Collection;

    public function getHotelsByRoomsRange(int $minRooms, int $maxRooms): Collection;

    public function restore(int $hotelId): Hotel;

    public function forceDelete(int $hotelId): bool;

    public function getTrashed(): Collection;
}
