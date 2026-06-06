<?php

declare(strict_types=1);

namespace App\Services\Parcel;

use App\Models\Parcel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ParcelService
{
    /**
     * Generate a secure 6-digit PIN and return the raw PIN.
     * The PIN is hashed before being stored in the database.
     */
    public function generateSecurityPin(Parcel $parcel): string
    {
        $rawPin = (string) rand(100000, 999999);
        
        $parcel->update([
            'security_pin_hash' => Hash::make($rawPin)
        ]);

        return $rawPin;
    }

    /**
     * Verify if the provided PIN matches the hashed PIN for the parcel.
     */
    public function verifyCollectionPin(Parcel $parcel, string $pin): bool
    {
        return Hash::check($pin, $parcel->security_pin_hash);
    }

    /**
     * Handle the collection process once PIN is verified.
     */
    public function collectParcel(Parcel $parcel, string $pin): bool
    {
        if (!$this->verifyCollectionPin($parcel, $pin)) {
            return false;
        }

        $parcel->update([
            'status' => 'collected',
            'metadata' => array_merge($parcel->metadata ?? [], [
                'collected_at' => now()->toIso8601String(),
            ])
        ]);

        return true;
    }
}
