<?php

namespace App\Repositories\Hotel;

use App\Models\Hotel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class HotelRepository implements HotelRepositoryInterface
{
    public function findById(int $id): ?Hotel
    {
        /** @var Hotel|null */
        return \App\Models\Hotel::query()->find($id);
    }

    public function findByNit(string $nit): ?Hotel
    {
        /** @var Hotel|null */
        return Hotel::byNit($nit)->first();
    }

    public function findByCity(string $city): Collection
    {
        return Hotel::byCity($city)->get();
    }

    public function findWithMinRooms(int $minRooms): Collection
    {
        return Hotel::withMinRooms($minRooms)->get();
    }

    public function search(string $term): Collection
    {
        return \App\Models\Hotel::query()->where(function (Builder $query) use ($term) {
            $query->where('name', 'LIKE', "%{$term}%")
                ->orWhere('nit', 'LIKE', "%{$term}%")
                ->orWhere('address', 'LIKE', "%{$term}%");
        })->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Hotel::query();

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    public function getWithRelations(array $relations = []): Collection
    {
        return Hotel::with($relations)->get();
    }

    public function create(array $data): Hotel
    {
        /** @var Hotel $hotel */
        $hotel = \App\Models\Hotel::query()->create([
            'name' => data_get($data, 'name'),
            'address' => data_get($data, 'address'),
            'city' => data_get($data, 'city'),
            'nit' => data_get($data, 'nit'),
            'max_rooms' => data_get($data, 'max_rooms'),
        ]);

        return $hotel->fresh();
    }

    public function update(Hotel $hotel, array $data): Hotel
    {
        $updateData = [];

        if (array_key_exists('name', $data)) {
            data_set($updateData, 'name', data_get($data, 'name'));
        }

        if (array_key_exists('address', $data)) {
            data_set($updateData, 'address', data_get($data, 'address'));
        }

        if (array_key_exists('city', $data)) {
            data_set($updateData, 'city', data_get($data, 'city'));
        }

        if (array_key_exists('nit', $data)) {
            data_set($updateData, 'nit', data_get($data, 'nit'));
        }

        if (array_key_exists('max_rooms', $data)) {
            data_set($updateData, 'max_rooms', data_get($data, 'max_rooms'));
        }

        if (! empty($updateData)) {
            $hotel->update($updateData);
        }

        return $hotel->fresh();
    }

    public function delete(Hotel $hotel): bool
    {
        return $hotel->delete();
    }

    public function getTopHotelsByRooms(int $limit = 10): Collection
    {
        return \App\Models\Hotel::query()->orderByDesc('max_rooms')
            ->limit($limit)
            ->get();
    }

    public function getHotelsByRoomsRange(int $minRooms, int $maxRooms): Collection
    {
        return \App\Models\Hotel::query()->whereBetween('max_rooms', [$minRooms, $maxRooms])
            ->orderBy('max_rooms')
            ->get();
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        if (data_get($filters, 'city')) {
            /** @var \App\Models\Builders\HotelBuilder $query */
            $query->byCity(data_get($filters, 'city'));
        }

        if (data_get($filters, 'min_rooms')) {
            /** @var \App\Models\Builders\HotelBuilder $query */
            $query->withMinRooms(data_get($filters, 'min_rooms'));
        }

        if ($search = data_get($filters, 'search')) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('nit', 'LIKE', "%{$search}%")
                    ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }

        $query->orderBy('name');
    }
}
