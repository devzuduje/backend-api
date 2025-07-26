<?php

namespace App\Repositories\Accommodation;

use App\Models\Accommodation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccommodationRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function findById(int $accommodationId): ?Accommodation;

    public function create(array $data): Accommodation;

    public function update(Accommodation $accommodation, array $data): Accommodation;

    public function delete(Accommodation $accommodation): bool;

    public function restore(int $accommodationId): Accommodation;

    public function findTrashed(int $accommodationId): ?Accommodation;
}
