<?php

declare(strict_types=1);

namespace App\Services\Booking;

use App\Exceptions\InsufficientSeatsException;
use App\Exceptions\SeatsAlreadyBookedException;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\SeatBooked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * @param array{
     *     schedule_id: string,
     *     user_id?: string|null,
     *     guest_name?: string|null,
     *     guest_phone?: string|null,
     *     seats: array<int, array{number: int, name: string, phone?: string|null}>,
     *     payment_method: string
     * } $data
     * @throws InsufficientSeatsException
     * @throws SeatsAlreadyBookedException
     */
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $schedule = Schedule::where('id', $data['schedule_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $seatCount = count($data['seats']);

            if ($schedule->available_seats < $seatCount) {
                throw new InsufficientSeatsException();
            }

            $seatNumbers = array_column($data['seats'], 'number');

            $takenSeats = SeatBooked::where('schedule_id', $schedule->id)
                ->whereIn('seat_number', $seatNumbers)
                ->lockForUpdate()
                ->pluck('seat_number');

            if ($takenSeats->isNotEmpty()) {
                throw new SeatsAlreadyBookedException($takenSeats);
            }

            $booking = Booking::create([
                'schedule_id' => $schedule->id,
                'user_id' => $data['user_id'] ?? null,
                'guest_name' => $data['guest_name'] ?? null,
                'guest_phone' => $data['guest_phone'] ?? null,
                'total_amount' => $schedule->base_price * $seatCount,
                'payment_status' => 'pending',
                'transaction_reference' => (string) Str::random(12), // Placeholder for gateway reference
                'expires_at' => now()->addMinutes(15),
            ]);

            foreach ($data['seats'] as $seatData) {
                SeatBooked::create([
                    'booking_id' => $booking->id,
                    'schedule_id' => $schedule->id,
                    'seat_number' => $seatData['number'],
                    'passenger_name' => $seatData['name'],
                    'passenger_phone' => $seatData['phone'] ?? null,
                ]);
            }

            $schedule->decrement('available_seats', $seatCount);

            return $booking;
        });
    }
}
