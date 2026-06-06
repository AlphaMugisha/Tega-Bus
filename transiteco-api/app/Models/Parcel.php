<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'sender_name',
        'sender_phone',
        'receiver_name',
        'receiver_phone',
        'origin_station_id',
        'destination_station_id',
        'schedule_id',
        'weight_kg',
        'price',
        'status',
        'security_pin_hash',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
