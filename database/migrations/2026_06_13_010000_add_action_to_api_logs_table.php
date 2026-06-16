<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_logs', function (Blueprint $table): void {
            if (! Schema::hasColumn('api_logs', 'action')) {
                $table->string('action')->nullable()->after('path')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('api_logs', function (Blueprint $table): void {
            if (Schema::hasColumn('api_logs', 'action')) {
                $table->dropColumn('action');
            }
        });
    }
};
