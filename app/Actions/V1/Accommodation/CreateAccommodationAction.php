<?php

namespace App\Actions\V1\Accommodation;

use App\Models\Accommodation;
use App\Repositories\Accommodation\AccommodationRepositoryInterface;

final readonly class CreateAccommodationAction
{
    public function __construct(
        private AccommodationRepositoryInterface $accommodationRepository
    ) {}

    public function __invoke(array $data): Accommodation
    {
        return $this->accommodationRepository->create($data);
    }
}
