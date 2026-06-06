<?php

declare(strict_types=1);

namespace App\Services\Ticket;

use App\Models\Booking;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generateForBooking(Booking $booking): string
    {
        $payload = json_encode([
            'booking_id' => $booking->id,
            'schedule_id' => $booking->schedule_id,
            'seats' => $booking->seatsBooked->pluck('seat_number'),
            'issued_at' => now()->toIso8601String(),
        ]);

        $hash = hash_hmac('sha256', $payload, config('app.qr_secret'));
        $booking->update(['qr_code_hash' => $hash]);

        $qrImage = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($hash);

        $path = "tickets/{$booking->id}.png";
        Storage::disk('s3')->put($path, $qrImage);

        return $path;
    }
}
