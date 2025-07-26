<?php

namespace App\Actions\V1\Accommodation;

use App\Repositories\Accommodation\AccommodationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class GetAccommodationsAction
{
    public function __construct(
        private AccommodationRepositoryInterface $accommodationRepository
    ) {}

    public function __invoke(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->accommodationRepository->getAllPaginated($filters, $perPage);
    }
}
