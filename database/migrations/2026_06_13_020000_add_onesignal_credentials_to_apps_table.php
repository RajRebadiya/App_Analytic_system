<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apps', function (Blueprint $table): void {
            if (! Schema::hasColumn('apps', 'onesignal_app_id')) {
                $table->string('onesignal_app_id')->nullable()->after('current_version');
            }

            if (! Schema::hasColumn('apps', 'onesignal_api_key')) {
                $table->string('onesignal_api_key', 4096)->nullable()->after('onesignal_app_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('apps', function (Blueprint $table): void {
            if (Schema::hasColumn('apps', 'onesignal_api_key')) {
                $table->dropColumn('onesignal_api_key');
            }

            if (Schema::hasColumn('apps', 'onesignal_app_id')) {
                $table->dropColumn('onesignal_app_id');
            }
        });
    }
};
