<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats_booked', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->smallInteger('seat_number');
            $table->string('passenger_name', 120);
            $table->string('passenger_phone', 20)->nullable();
            $table->timestamps();

            $table->unique(['booking_id', 'seat_number']);
            // The critical composite unique index preventing double-booking via schedule_id
            // Note: Since schedule_id is in bookings, we might need a denormalized column or a trigger
            // for absolute DB-level constraint across schedules if we don't rely on app logic.
            // But per Section 6A, we use app-level lock and unique constraint on (booking_id, seat_number).
            // Actually Section 4 says: INDEX UNIQUE: (schedule_id_via_booking, seat_number)
            // I'll add schedule_id here for easier indexing and constraint enforcement.
            $table->foreignUuid('schedule_id')->constrained('schedules');
            $table->unique(['schedule_id', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats_booked');
    }
};
