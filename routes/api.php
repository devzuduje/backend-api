<?php

use Illuminate\Support\Facades\Route;

// API V1 Routes
Route::prefix('v1')->group(base_path('routes/api/v1.php'));

// Future API versions can be added here:
// Route::prefix('v2')->group(base_path('routes/api/v2.php'));
