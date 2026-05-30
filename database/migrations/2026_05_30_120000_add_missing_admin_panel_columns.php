<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_versions', function (Blueprint $table): void {
            if (! Schema::hasColumn('app_versions', 'change_log')) {
                $table->text('change_log')->nullable()->after('message');
            }
        });

        Schema::table('notifications', function (Blueprint $table): void {
            if (! Schema::hasColumn('notifications', 'redirect_screen')) {
                $table->string('redirect_screen')->nullable()->after('send_to');
            }

            if (! Schema::hasColumn('notifications', 'redirect_data')) {
                $table->json('redirect_data')->nullable()->after('redirect_screen');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table): void {
            if (Schema::hasColumn('notifications', 'redirect_data')) {
                $table->dropColumn('redirect_data');
            }

            if (Schema::hasColumn('notifications', 'redirect_screen')) {
                $table->dropColumn('redirect_screen');
            }
        });

        Schema::table('app_versions', function (Blueprint $table): void {
            if (Schema::hasColumn('app_versions', 'change_log')) {
                $table->dropColumn('change_log');
            }
        });
    }
};
