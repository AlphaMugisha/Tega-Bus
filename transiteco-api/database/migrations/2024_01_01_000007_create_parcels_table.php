<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parcels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sender_name', 120);
            $table->string('sender_phone', 20);
            $table->string('receiver_name', 120);
            $table->string('receiver_phone', 20);
            $table->foreignUuid('origin_station_id')->constrained('routes');
            $table->foreignUuid('destination_station_id')->constrained('routes');
            $table->foreignUuid('schedule_id')->nullable()->constrained('schedules');
            $table->decimal('weight_kg', 6, 2);
            $table->integer('price');
            $table->string('status', 20)->default('pending');
            $table->string('security_pin_hash', 255);
            $table->jsonb('metadata')->default('{}');
            $table->timestamps();

            $table->index('sender_phone');
            $table->index('receiver_phone');
            $table->index('status');
            $table->index('schedule_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcels');
    }
};
