<?php

namespace App\Actions\V1\RoomType;

use App\Models\RoomType;
use App\Repositories\RoomType\RoomTypeRepositoryInterface;

final readonly class CreateRoomTypeAction
{
    public function __construct(
        private RoomTypeRepositoryInterface $roomTypeRepository
    ) {}

    public function __invoke(array $data): RoomType
    {
        return $this->roomTypeRepository->create($data);
    }
}
