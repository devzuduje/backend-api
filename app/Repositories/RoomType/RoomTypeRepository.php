<?php

namespace App\Repositories\RoomType;

use App\Models\RoomType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class RoomTypeRepository implements RoomTypeRepositoryInterface
{
    public function findById(int $id): ?RoomType
    {
        /** @var RoomType|null */
        return RoomType::query()->find($id);
    }

    public function findByCode(string $code): ?RoomType
    {
        /** @var RoomType|null */
        return RoomType::byCode($code)->first();
    }

    public function findByName(string $name): ?RoomType
    {
        /** @var RoomType|null */
        return RoomType::byName($name)->first();
    }

    public function search(string $term): Collection
    {
        return RoomType::query()->where(function (Builder $query) use ($term) {
            $query->where('name', 'LIKE', "%{$term}%")
                ->orWhere('code', 'LIKE', "%{$term}%");
        })->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = RoomType::query();

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    public function getWithRelations(array $relations = []): Collection
    {
        return RoomType::with($relations)->get();
    }

    public function create(array $data): RoomType
    {
        /** @var RoomType $roomType */
        $roomType = RoomType::query()->create([
            'name' => data_get($data, 'name'),
            'code' => data_get($data, 'code'),
        ]);

        return $roomType->fresh();
    }

    public function update(RoomType $roomType, array $data): RoomType
    {
        $updateData = [];

        if (array_key_exists('name', $data)) {
            data_set($updateData, 'name', data_get($data, 'name'));
        }

        if (array_key_exists('code', $data)) {
            data_set($updateData, 'code', data_get($data, 'code'));
        }

        if (! empty($updateData)) {
            $roomType->update($updateData);
        }

        return $roomType->fresh();
    }

    public function delete(RoomType $roomType): bool
    {
        return $roomType->delete();
    }

    public function restore(int $roomTypeId): RoomType
    {
        /** @var RoomType $roomType */
        $roomType = RoomType::withTrashed()->findOrFail($roomTypeId);

        $roomType->restore();

        return $roomType->fresh();
    }

    public function forceDelete(int $roomTypeId): bool
    {
        /** @var RoomType $roomType */
        $roomType = RoomType::withTrashed()->findOrFail($roomTypeId);

        return $roomType->forceDelete();
    }

    public function getTrashed(): Collection
    {
        return RoomType::onlyTrashed()->get();
    }

    public function getPopularRoomTypes(int $limit = 10): Collection
    {
        return \App\Models\RoomType::query()->withCount('hotelRooms')
            ->orderByDesc('hotel_rooms_count')
            ->limit($limit)
            ->get();
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        if ($search = data_get($filters, 'search')) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        if (data_get($filters, 'with_trashed')) {
            /** @var \App\Models\Builders\RoomTypeBuilder $query */
            $query->withTrashed();
        } elseif (data_get($filters, 'only_trashed')) {
            /** @var \App\Models\Builders\RoomTypeBuilder $query */
            $query->onlyTrashed();
        }

        $query->orderBy('name');
    }
}
