<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')->nullable()->constrained('bookings');
            $table->foreignUuid('parcel_id')->nullable()->constrained('parcels');
            $table->string('gateway', 30);
            $table->string('reference', 120);
            $table->integer('amount');
            $table->string('transaction_type', 10);
            $table->string('status', 20);
            $table->jsonb('raw_payload')->default('{}');
            $table->timestamps();

            $table->index('booking_id');
            $table->index('parcel_id');
            $table->index('reference');
            $table->index(['gateway', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
