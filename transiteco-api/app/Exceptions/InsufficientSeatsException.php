<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InsufficientSeatsException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'Not enough seats available for this schedule.',
        ], 422);
    }
}
