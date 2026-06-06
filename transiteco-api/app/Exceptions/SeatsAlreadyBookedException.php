<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class SeatsAlreadyBookedException extends Exception
{
    public function __construct(protected Collection $takenSeats)
    {
        parent::__construct('Some of the selected seats are already booked.');
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'taken_seats' => $this->takenSeats,
        ], 422);
    }
}
