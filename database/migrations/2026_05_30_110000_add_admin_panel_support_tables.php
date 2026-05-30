<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role', 32)->default('admin')->after('password')->index();
            $table->string('avatar')->nullable()->after('remember_token');
        });

        Schema::create('api_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('method', 10);
            $table->string('path');
            $table->unsignedSmallInteger('status_code')->nullable()->index();
            $table->unsignedInteger('response_time_ms')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('app_id')->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index(['path', 'created_at']);
            $table->index(['status_code', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_logs');

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['role', 'avatar']);
        });
    }
};
