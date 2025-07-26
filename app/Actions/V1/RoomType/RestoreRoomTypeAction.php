<?php

namespace App\Actions\V1\RoomType;

use App\Models\RoomType;
use App\Repositories\RoomType\RoomTypeRepositoryInterface;

final readonly class RestoreRoomTypeAction
{
    public function __construct(
        private RoomTypeRepositoryInterface $roomTypeRepository
    ) {}

    public function __invoke(int $roomTypeId): RoomType
    {
        return $this->roomTypeRepository->restore($roomTypeId);
    }
}
