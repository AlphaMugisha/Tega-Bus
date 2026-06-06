<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('origin_station', 120);
            $table->string('destination_station', 120);
            $table->decimal('distance_km', 7, 2)->nullable();
            $table->string('estimated_duration')->nullable(); // INTERVAL in Postgres, using string for representation or native interval if supported
            $table->jsonb('path_coordinates')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['origin_station', 'destination_station']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
