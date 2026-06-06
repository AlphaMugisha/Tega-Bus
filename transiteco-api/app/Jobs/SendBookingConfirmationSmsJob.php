<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Booking;
use App\Services\Notification\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBookingConfirmationSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(protected Booking $booking)
    {
    }

    public function handle(SmsService $smsService): void
    {
        $smsService->sendBookingConfirmation($booking->load(['seatsBooked', 'user']));
    }
}
