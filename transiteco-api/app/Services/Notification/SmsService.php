<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function sendBookingConfirmation(Booking $booking): void
    {
        $phone = $booking->user ? $booking->user->phone : $booking->guest_phone;
        $message = "Your booking for TransitEco is confirmed! Booking ID: {$booking->id}. Seats: " . $booking->seatsBooked->pluck('seat_number')->implode(', ');

        $this->sendSms($phone, $message);
    }

    protected function sendSms(string $phone, string $message): void
    {
        $url = config('services.sms.gateway_url');
        $key = config('services.sms.gateway_key');

        if (!$url || !$key) {
            Log::info("SMS would be sent to {$phone}: {$message}");
            return;
        }

        Http::post($url, [
            'to' => $phone,
            'message' => $message,
            'api_key' => $key,
        ]);
    }
}
