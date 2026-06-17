<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_network_settings', function (Blueprint $table): void {
            $table->json('others')->nullable()->after('more_app_url');
        });
    }

    public function down(): void
    {
        Schema::table('ad_network_settings', function (Blueprint $table): void {
            $table->dropColumn('others');
        });
    }
};
