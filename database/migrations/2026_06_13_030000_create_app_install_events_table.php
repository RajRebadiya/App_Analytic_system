<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_install_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->string('device_id', 128);
            $table->string('device_name')->nullable();
            $table->string('device_brand')->nullable();
            $table->string('android_version', 32)->nullable();
            $table->string('country_code', 8)->nullable()->index();
            $table->string('app_version', 32);
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
            $table->index(['app_id', 'created_at']);
            $table->index(['app_id', 'device_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_install_events');
    }
};
