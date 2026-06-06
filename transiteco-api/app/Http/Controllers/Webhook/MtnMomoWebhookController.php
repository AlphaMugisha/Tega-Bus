<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateTicketQrJob;
use App\Jobs\SendBookingConfirmationSmsJob;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MtnMomoWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        $reference = $payload['externalId'] ?? $payload['transactionReference'] ?? null;

        if (!$reference) {
            return response()->json(['message' => 'Reference missing'], 400);
        }

        $booking = Booking::where('transaction_reference', $reference)->first();

        if (!$booking) {
            Log::warning("Booking not found for reference: {$reference}");
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Idempotency check
        if ($booking->payment_status === 'paid') {
            return response()->json(['message' => 'Already processed']);
        }

        $status = $payload['status'] === 'SUCCESSFUL' ? 'paid' : 'failed';

        DB::transaction(function () use ($booking, $payload, $status) {
            $booking->update(['payment_status' => $status]);

            Transaction::create([
                'booking_id' => $booking->id,
                'gateway' => 'mtn_momo',
                'reference' => $payload['financialTransactionId'] ?? $booking->transaction_reference,
                'amount' => $payload['amount'] ?? $booking->total_amount,
                'transaction_type' => 'credit',
                'status' => $payload['status'] === 'SUCCESSFUL' ? 'success' : 'failed',
                'raw_payload' => $payload,
            ]);

            if ($status === 'paid') {
                GenerateTicketQrJob::dispatch($booking)->onQueue('high');
                SendBookingConfirmationSmsJob::dispatch($booking)->onQueue('high');
            }
        });

        return response()->json(['message' => 'Processed successfully']);
    }
}
