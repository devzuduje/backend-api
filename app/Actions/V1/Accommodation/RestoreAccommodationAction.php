<?php

namespace App\Actions\V1\Accommodation;

use App\Models\Accommodation;
use App\Repositories\Accommodation\AccommodationRepositoryInterface;

final readonly class RestoreAccommodationAction
{
    public function __construct(
        private AccommodationRepositoryInterface $accommodationRepository
    ) {}

    public function __invoke(int $accommodationId): Accommodation
    {
        return $this->accommodationRepository->restore($accommodationId);
    }
}
