<?php

namespace App\Repositories\RoomType;

use App\Models\RoomType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface RoomTypeRepositoryInterface
{
    public function findById(int $id): ?RoomType;

    public function findByCode(string $code): ?RoomType;

    public function findByName(string $name): ?RoomType;

    public function search(string $term): Collection;

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function getWithRelations(array $relations = []): Collection;

    public function create(array $data): RoomType;

    public function update(RoomType $roomType, array $data): RoomType;

    public function delete(RoomType $roomType): bool;

    public function restore(int $roomTypeId): RoomType;

    public function forceDelete(int $roomTypeId): bool;

    public function getTrashed(): Collection;

    public function getPopularRoomTypes(int $limit = 10): Collection;
}
