<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'origin_station',
        'destination_station',
        'distance_km',
        'estimated_duration',
        'path_coordinates',
        'is_active',
    ];

    protected $casts = [
        'path_coordinates' => 'array',
        'is_active' => 'boolean',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
