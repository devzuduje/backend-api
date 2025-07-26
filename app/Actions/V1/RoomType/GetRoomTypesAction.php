<?php

namespace App\Actions\V1\RoomType;

use App\Repositories\RoomType\RoomTypeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class GetRoomTypesAction
{
    public function __construct(
        private RoomTypeRepositoryInterface $roomTypeRepository
    ) {}

    public function __invoke(array $filters = []): LengthAwarePaginator
    {
        $perPage = data_get($filters, 'per_page', 15);

        return $this->roomTypeRepository->getPaginated($filters, $perPage);
    }
}
