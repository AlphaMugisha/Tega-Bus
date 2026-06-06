<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory; // AuditLog might not need HasUuid if it uses BigSerial PK as per Section 4

    protected $fillable = [
        'user_id',
        'action',
        'table_affected',
        'row_uuid',
        'ip_address',
        'user_agent',
        'diff',
    ];

    protected $casts = [
        'diff' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
