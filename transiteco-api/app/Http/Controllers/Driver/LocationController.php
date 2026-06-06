<?php

declare(strict_types=1);

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\BusLocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Store the latest GPS coordinates from the driver's phone.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'schedule_id' => ['required', 'uuid', 'exists:schedules,id'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'heading' => ['nullable', 'numeric'],
            'speed' => ['nullable', 'numeric'],
        ]);

        $location = BusLocation::create([
            'schedule_id' => $request->schedule_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'heading' => $request->heading,
            'speed' => $request->speed,
        ]);

        return response()->json([
            'message' => 'Location updated',
            'data' => $location
        ]);
    }

    /**
     * Get the latest location for a specific schedule (for passenger view).
     */
    public function show(string $scheduleId): JsonResponse
    {
        $location = BusLocation::where('schedule_id', $scheduleId)
            ->latest()
            ->first();

        if (!$location) {
            return response()->json(['message' => 'No location data available'], 404);
        }

        return response()->json([
            'data' => $location
        ]);
    }
}
