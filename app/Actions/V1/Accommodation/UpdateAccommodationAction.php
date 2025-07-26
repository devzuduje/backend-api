<?php

namespace App\Actions\V1\Accommodation;

use App\Models\Accommodation;
use App\Repositories\Accommodation\AccommodationRepositoryInterface;

final readonly class UpdateAccommodationAction
{
    public function __construct(
        private AccommodationRepositoryInterface $accommodationRepository
    ) {}

    public function __invoke(Accommodation $accommodation, array $data): Accommodation
    {
        return $this->accommodationRepository->update($accommodation, $data);
    }
}
