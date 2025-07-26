<?php

use Illuminate\Support\Facades\Route;

// Public authentication routes
Route::prefix('auth')->group(function () {
    Route::post('/register', App\Http\Controllers\Api\V1\Auth\RegisterController::class);
    Route::post('/login', App\Http\Controllers\Api\V1\Auth\LoginController::class);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth protected routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', App\Http\Controllers\Api\V1\Auth\LogoutController::class);
    });

    // User routes
    Route::get('/user', App\Http\Controllers\Api\V1\UserController::class);

    // Hotel management routes
    Route::apiResource('hotels', App\Http\Controllers\Api\V1\HotelController::class);
    Route::post('hotels/{hotelId}/restore', [App\Http\Controllers\Api\V1\HotelController::class, 'restore']);

    // Room Type management routes
    Route::apiResource('room-types', App\Http\Controllers\Api\V1\RoomTypeController::class);
    Route::post('room-types/{roomTypeId}/restore', [App\Http\Controllers\Api\V1\RoomTypeController::class, 'restore']);

    // TODO: Add remaining resource controllers when created:
    // Route::apiResource('accommodations', App\Http\Controllers\Api\V1\AccommodationController::class);
    // Route::apiResource('hotel-rooms', App\Http\Controllers\Api\V1\HotelRoomController::class);
});
