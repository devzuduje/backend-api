<?php

namespace App\Repositories\Accommodation;

use App\Models\Accommodation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class AccommodationRepository implements AccommodationRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Accommodation::query();

        if (data_get($filters, 'search')) {
            $search = data_get($filters, 'search');
            $query->where(function ($q) use ($search) {
                $q->where(column: 'name', operator: 'ILIKE', value: "%{$search}%")
                    ->orWhere(column: 'code', operator: 'ILIKE', value: "%{$search}%");
            });
        }

        if (data_get($filters, 'min_capacity')) {
            $query->where(column: 'capacity', operator: '>=', value: data_get($filters, 'min_capacity'));
        }

        if (data_get($filters, 'with_trashed')) {
            $query->withTrashed();
        } elseif (data_get($filters, 'only_trashed')) {
            $query->onlyTrashed();
        }

        return $query->orderBy(column: 'created_at', direction: 'desc')
            ->paginate(perPage: $perPage);
    }

    public function findById(int $accommodationId): ?Accommodation
    {
        return \App\Models\Accommodation::query()->find($accommodationId);
    }

    public function create(array $data): Accommodation
    {
        return \App\Models\Accommodation::query()->create([
            'name' => data_get($data, 'name'),
            'code' => data_get($data, 'code'),
            'capacity' => data_get($data, 'capacity'),
        ]);
    }

    public function update(Accommodation $accommodation, array $data): Accommodation
    {
        $updateData = [];

        if (array_key_exists('name', $data)) {
            data_set($updateData, 'name', data_get($data, 'name'));
        }

        if (array_key_exists('code', $data)) {
            data_set($updateData, 'code', data_get($data, 'code'));
        }

        if (array_key_exists('capacity', $data)) {
            data_set($updateData, 'capacity', data_get($data, 'capacity'));
        }

        if (! empty($updateData)) {
            $accommodation->update($updateData);
        }

        return $accommodation->fresh();
    }

    public function delete(Accommodation $accommodation): bool
    {
        return $accommodation->delete();
    }

    public function restore(int $accommodationId): Accommodation
    {
        /** @var Accommodation $accommodation */
        $accommodation = Accommodation::withTrashed()->findOrFail($accommodationId);
        $accommodation->restore();

        return $accommodation->fresh();
    }

    public function findTrashed(int $accommodationId): ?Accommodation
    {
        return Accommodation::onlyTrashed()->find($accommodationId);
    }
}
