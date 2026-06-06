<?php

declare(strict_types=1);

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\CreateBookingRequest;
use App\Services\Booking\BookingService;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService)
    {
    }

    public function store(CreateBookingRequest $request): JsonResponse
    {
        $booking = $this->bookingService->createBooking(
            array_merge($request->validated(), [
                'user_id' => $request->user()?->id,
            ])
        );

        return response()->json([
            'message' => 'Booking created successfully.',
            'data' => $booking->load('seatsBooked'),
        ], 201);
    }
}
