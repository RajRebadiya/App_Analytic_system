<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table): void {
            if (! Schema::hasColumn('notifications', 'onesignal_response')) {
                $table->json('onesignal_response')->nullable()->after('image');
            }

            if (! Schema::hasColumn('notifications', 'status')) {
                $table->string('status', 24)->default('pending')->index()->after('onesignal_response');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table): void {
            if (Schema::hasColumn('notifications', 'status')) {
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('notifications', 'onesignal_response')) {
                $table->dropColumn('onesignal_response');
            }
        });
    }
};
