<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Driver\LocationController;
use App\Http\Controllers\Passenger\BookingController;
use App\Http\Controllers\Passenger\RouteController;
use App\Http\Controllers\Passenger\ScheduleController;
use App\Http\Controllers\Passenger\UserBookingController;
use App\Http\Controllers\Webhook\MtnMomoWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    // ── Auth ──────────────────────────────────────────────────────────
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login',    [AuthController::class, 'login']);

    // ── Public discovery ──────────────────────────────────────────────
    Route::get('/routes',                            [RouteController::class,   'index']);
    Route::get('/schedules',                         [ScheduleController::class, 'index']);
    Route::get('/schedules/{schedule}',              [ScheduleController::class, 'show']);
    Route::get('/schedules/{schedule}/location',     [LocationController::class, 'show']);

    // ── Booking (guest or auth) ───────────────────────────────────────
    Route::post('/bookings', [BookingController::class, 'store']);

    // ── Driver ───────────────────────────────────────────────────────
    Route::post('/driver/location', [LocationController::class, 'store']);

    // ── Authenticated passenger ───────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile',          [AuthController::class,       'profile']);
        Route::get('/user/bookings',    [UserBookingController::class, 'index']);
        Route::get('/user/bookings/{id}', [UserBookingController::class, 'show']);
    });

    // ── Webhooks ─────────────────────────────────────────────────────
    Route::post('/webhooks/mtn-momo', [MtnMomoWebhookController::class, 'handle'])
        ->middleware(\App\Http\Middleware\WebhookSignatureMiddleware::class);
});
