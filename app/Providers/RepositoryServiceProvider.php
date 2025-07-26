<?php

namespace App\Providers;

use App\Repositories\Hotel\HotelRepository;
use App\Repositories\Hotel\HotelRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            HotelRepositoryInterface::class,
            HotelRepository::class
        );

        $this->app->bind(
            \App\Repositories\RoomType\RoomTypeRepositoryInterface::class,
            \App\Repositories\RoomType\RoomTypeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Accommodation\AccommodationRepositoryInterface::class,
            \App\Repositories\Accommodation\AccommodationRepository::class
        );

        $this->app->bind(
            \App\Repositories\HotelRoom\HotelRoomRepositoryInterface::class,
            \App\Repositories\HotelRoom\HotelRoomRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
