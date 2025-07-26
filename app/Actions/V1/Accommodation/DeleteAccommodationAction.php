<?php

namespace App\Actions\V1\Accommodation;

use App\Models\Accommodation;
use App\Repositories\Accommodation\AccommodationRepositoryInterface;

final readonly class DeleteAccommodationAction
{
    public function __construct(
        private AccommodationRepositoryInterface $accommodationRepository
    ) {}

    public function __invoke(Accommodation $accommodation): bool
    {
        return $this->accommodationRepository->delete($accommodation);
    }
}
