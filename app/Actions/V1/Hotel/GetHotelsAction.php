<?php

namespace App\Actions\V1\Hotel;

use App\Repositories\Hotel\HotelRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class GetHotelsAction
{
    public function __construct(
        private HotelRepositoryInterface $hotelRepository
    ) {}

    public function __invoke(array $filters = []): LengthAwarePaginator
    {
        $perPage = data_get($filters, 'per_page', 15);

        return $this->hotelRepository->getPaginated($filters, $perPage);
    }
}
