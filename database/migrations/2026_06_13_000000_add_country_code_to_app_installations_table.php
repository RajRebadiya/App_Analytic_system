<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_installations', function (Blueprint $table): void {
            if (! Schema::hasColumn('app_installations', 'country_code')) {
                $table->string('country_code', 8)->nullable()->after('android_version')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('app_installations', function (Blueprint $table): void {
            if (Schema::hasColumn('app_installations', 'country_code')) {
                $table->dropColumn('country_code');
            }
        });
    }
};
