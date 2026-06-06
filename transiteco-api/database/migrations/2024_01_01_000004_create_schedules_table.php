<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('bus_id')->constrained('buses');
            $table->foreignUuid('route_id')->constrained('routes');
            $table->timestampTz('departure_time');
            $table->timestampTz('arrival_time');
            $table->integer('base_price');
            $table->string('status', 20)->default('scheduled');
            $table->smallInteger('available_seats');
            $table->timestamps();

            $table->index(['route_id', 'departure_time']);
            $table->index('status');
            $table->index('bus_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
