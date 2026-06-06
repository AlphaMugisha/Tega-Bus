<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeatBooked extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'seats_booked';

    protected $fillable = [
        'booking_id',
        'schedule_id',
        'seat_number',
        'passenger_name',
        'passenger_phone',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
