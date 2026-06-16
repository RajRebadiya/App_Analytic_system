<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apps', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('package_name')->unique();
            $table->string('current_version')->default('1.0.0');
            $table->string('onesignal_app_id')->nullable();
            $table->string('onesignal_api_key', 4096)->nullable();
            $table->string('status', 24)->default('active')->index();
            $table->timestamps();
        });

        Schema::create('app_installations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->string('device_id', 128);
            $table->string('device_name')->nullable();
            $table->string('device_brand')->nullable();
            $table->string('android_version', 32)->nullable();
            $table->string('country_code', 8)->nullable()->index();
            $table->string('app_version', 32);
            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('installed_at')->nullable();
            $table->timestamp('last_active_at')->nullable()->index();
            $table->timestamps();
            $table->unique(['app_id', 'device_id']);
            $table->index(['device_id', 'app_id']);
            $table->index(['app_id', 'installed_at']);
        });

        Schema::create('device_tokens', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->string('device_id', 128);
            $table->text('fcm_token');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->unique(['app_id', 'device_id']);
            $table->index(['app_id', 'is_active']);
        });

        Schema::create('advertisements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('redirect_type', 32);
            $table->string('redirect_value')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->unsignedInteger('priority')->default(0);
            $table->string('status', 24)->default('active')->index();
            $table->timestamps();
            $table->index(['app_id', 'status', 'priority']);
            $table->index(['start_date', 'end_date']);
        });

        Schema::create('notifications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('notification_type', 32)->default('general');
            $table->string('send_to', 32)->default('all');
            $table->string('redirect_screen')->nullable();
            $table->json('redirect_data')->nullable();
            $table->unsignedBigInteger('total_sent')->default(0);
            $table->unsignedBigInteger('total_failed')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['app_id', 'created_at']);
        });

        Schema::create('notification_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('notification_id')->constrained('notifications')->cascadeOnDelete();
            $table->string('device_id', 128)->nullable();
            $table->text('fcm_token');
            $table->string('status', 24)->index();
            $table->json('response')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->index(['notification_id', 'status']);
        });

        Schema::create('app_versions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->string('latest_version');
            $table->string('min_supported_version');
            $table->boolean('force_update')->default(false);
            $table->boolean('maintenance_mode')->default(false);
            $table->string('apk_url')->nullable();
            $table->text('message')->nullable();
            $table->text('change_log')->nullable();
            $table->timestamps();
            $table->index(['app_id', 'created_at']);
        });

        Schema::create('app_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->string('device_id', 128);
            $table->string('event_name', 64);
            $table->json('event_data')->nullable();
            $table->timestamps();
            $table->index(['app_id', 'event_name', 'created_at']);
            $table->index(['app_id', 'device_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_events');
        Schema::dropIfExists('app_versions');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('advertisements');
        Schema::dropIfExists('device_tokens');
        Schema::dropIfExists('app_installations');
        Schema::dropIfExists('apps');
    }
};
