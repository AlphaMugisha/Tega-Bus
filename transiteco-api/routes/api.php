<?php

use App\Http\Controllers\Passenger\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::post('/driver/location', [\App\Http\Controllers\Driver\LocationController::class, 'store']);
    Route::get('/schedules/{schedule}/location', [\App\Http\Controllers\Driver\LocationController::class, 'show']);
    Route::post('/webhooks/mtn-momo', [\App\Http\Controllers\Webhook\MtnMomoWebhookController::class, 'handle'])
        ->middleware(\App\Http\Middleware\WebhookSignatureMiddleware::class);
});
