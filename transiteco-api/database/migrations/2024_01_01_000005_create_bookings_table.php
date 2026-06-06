<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('schedule_id')->constrained('schedules');
            $table->foreignUuid('user_id')->nullable()->constrained('users');
            $table->string('guest_name', 120)->nullable();
            $table->string('guest_phone', 20)->nullable();
            $table->integer('total_amount');
            $table->string('payment_status', 20)->default('pending');
            $table->string('transaction_reference', 80)->unique()->nullable();
            $table->string('qr_code_hash', 255)->unique()->nullable();
            $table->timestampTz('expires_at')->nullable();
            $table->timestamps();

            $table->index('schedule_id');
            $table->index('user_id');
            $table->index('payment_status');
            $table->index('qr_code_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
