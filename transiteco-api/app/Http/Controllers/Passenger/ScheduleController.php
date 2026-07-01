<?php

declare(strict_types=1);

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\SeatBooked;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'origin'      => ['nullable', 'string'],
            'destination' => ['nullable', 'string'],
            'date'        => ['nullable', 'date'],
        ]);

        $query = Schedule::with(['route', 'bus.company'])
            ->where('status', '!=', 'cancelled')
            ->where('departure_time', '>', now());

        if ($request->filled('origin')) {
            $query->whereHas('route', fn ($q) =>
                $q->where('origin_station', 'like', '%' . $request->origin . '%')
            );
        }

        if ($request->filled('destination')) {
            $query->whereHas('route', fn ($q) =>
                $q->where('destination_station', 'like', '%' . $request->destination . '%')
            );
        }

        if ($request->filled('date')) {
            $date = $request->date;
            $query->whereDate('departure_time', $date);
        }

        $schedules = $query->orderBy('departure_time')->get();

        return response()->json(['data' => $schedules]);
    }

    public function show(string $id): JsonResponse
    {
        $schedule = Schedule::with(['route', 'bus.company'])->findOrFail($id);

        $bookedSeats = SeatBooked::where('schedule_id', $id)->pluck('seat_number');

        return response()->json([
            'data' => $schedule,
            'booked_seats' => $bookedSeats,
        ]);
    }
}
