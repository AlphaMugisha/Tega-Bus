<?php

declare(strict_types=1);

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'schedule_id' => ['required', 'uuid', 'exists:schedules,id'],
            'seats' => ['required', 'array', 'min:1', 'max:4'],
            'seats.*.number' => ['required', 'integer', 'min:1'],
            'seats.*.name' => ['required', 'string', 'max:120'],
            'seats.*.phone' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['required', 'string', 'in:mtn_momo,airtel_money,card'],
            'guest_name' => ['required_without:user_id', 'nullable', 'string', 'max:120'],
            'guest_phone' => ['required_without:user_id', 'nullable', 'string', 'max:20'],
        ];
    }
}
