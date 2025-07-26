<?php

use Illuminate\Support\Facades\Route;

// Public authentication routes
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/register', App\Http\Controllers\Api\V1\Auth\RegisterController::class)->name('register');
    Route::post('/login', App\Http\Controllers\Api\V1\Auth\LoginController::class)->name('login');
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth protected routes
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/logout', App\Http\Controllers\Api\V1\Auth\LogoutController::class)->name('logout');
    });

    // User routes
    Route::get('/user', App\Http\Controllers\Api\V1\UserController::class)->name('user.profile');

    // Hotel management routes
    Route::apiResource('hotels', App\Http\Controllers\Api\V1\HotelController::class);
    Route::post('hotels/{hotelId}/restore', [App\Http\Controllers\Api\V1\HotelController::class, 'restore']);

    // Room Type management routes
    Route::apiResource('room-types', App\Http\Controllers\Api\V1\RoomTypeController::class);
    Route::post('room-types/{roomTypeId}/restore', [App\Http\Controllers\Api\V1\RoomTypeController::class, 'restore']);

    // Accommodation management routes
    Route::apiResource('accommodations', App\Http\Controllers\Api\V1\AccommodationController::class);
    Route::post('accommodations/{accommodationId}/restore', [App\Http\Controllers\Api\V1\AccommodationController::class, 'restore']);

    // Hotel Room management routes
    Route::apiResource('hotel-rooms', App\Http\Controllers\Api\V1\HotelRoomController::class);
    Route::post('hotel-rooms/{hotelRoomId}/restore', [App\Http\Controllers\Api\V1\HotelRoomController::class, 'restore']);
});
