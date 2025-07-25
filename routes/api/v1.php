<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::prefix('auth')->group(function () {
    // Public auth routes
    Route::post('/register', App\Http\Controllers\Api\V1\Auth\RegisterController::class);
    Route::post('/login', App\Http\Controllers\Api\V1\Auth\LoginController::class);
    
    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', App\Http\Controllers\Api\V1\Auth\LogoutController::class);
    });
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // TODO: Add resource controllers when created:
    // Route::apiResource('hotels', App\Http\Controllers\Api\V1\HotelController::class);
    // Route::apiResource('room-types', App\Http\Controllers\Api\V1\RoomTypeController::class);
    // Route::apiResource('accommodations', App\Http\Controllers\Api\V1\AccommodationController::class);
    // Route::apiResource('hotel-rooms', App\Http\Controllers\Api\V1\HotelRoomController::class);
});