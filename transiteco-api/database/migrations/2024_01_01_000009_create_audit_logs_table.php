<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignUuid('user_id')->nullable()->constrained('users');
            $table->string('action', 60);
            $table->string('table_affected', 60)->nullable();
            $table->uuid('row_uuid')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->jsonb('diff')->default('{}');
            $table->timestamps();

            $table->index('user_id');
            $table->index(['table_affected', 'row_uuid']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
