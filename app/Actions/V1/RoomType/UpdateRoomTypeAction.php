<?php

namespace App\Actions\V1\RoomType;

use App\Models\RoomType;
use App\Repositories\RoomType\RoomTypeRepositoryInterface;

final readonly class UpdateRoomTypeAction
{
    public function __construct(
        private RoomTypeRepositoryInterface $roomTypeRepository
    ) {}

    public function __invoke(RoomType $roomType, array $data): RoomType
    {
        return $this->roomTypeRepository->update($roomType, $data);
    }
}
