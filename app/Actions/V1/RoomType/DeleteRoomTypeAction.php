<?php

namespace App\Actions\V1\RoomType;

use App\Models\RoomType;
use App\Repositories\RoomType\RoomTypeRepositoryInterface;

final readonly class DeleteRoomTypeAction
{
    public function __construct(
        private RoomTypeRepositoryInterface $roomTypeRepository
    ) {}

    public function __invoke(RoomType $roomType): bool
    {
        return $this->roomTypeRepository->delete($roomType);
    }
}
