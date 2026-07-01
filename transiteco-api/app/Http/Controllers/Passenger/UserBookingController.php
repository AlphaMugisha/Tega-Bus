<?php

declare(strict_types=1);

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserBookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $bookings = Booking::with(['schedule.route', 'schedule.bus.company', 'seatsBooked'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['data' => $bookings]);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $booking = Booking::with(['schedule.route', 'schedule.bus.company', 'seatsBooked'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json(['data' => $booking]);
    }
}
