<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apps', function (Blueprint $table): void {
            if (Schema::hasColumn('apps', 'app_id')) {
                $table->dropUnique('apps_app_id_unique');
            }

            if (Schema::hasColumn('apps', 'api_key')) {
                $table->dropUnique('apps_api_key_unique');
            }
        });

        Schema::table('apps', function (Blueprint $table): void {
            $columns = array_values(array_filter([
                Schema::hasColumn('apps', 'app_id') ? 'app_id' : null,
                Schema::hasColumn('apps', 'api_key') ? 'api_key' : null,
                Schema::hasColumn('apps', 'min_supported_version') ? 'min_supported_version' : null,
                Schema::hasColumn('apps', 'latest_version') ? 'latest_version' : null,
                Schema::hasColumn('apps', 'force_update') ? 'force_update' : null,
                Schema::hasColumn('apps', 'maintenance_mode') ? 'maintenance_mode' : null,
            ]));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }

    public function down(): void
    {
        Schema::table('apps', function (Blueprint $table): void {
            if (! Schema::hasColumn('apps', 'app_id')) {
                $table->string('app_id')->nullable()->unique()->after('name');
            }

            if (! Schema::hasColumn('apps', 'api_key')) {
                $table->string('api_key', 96)->nullable()->unique()->after('package_name');
            }

            if (! Schema::hasColumn('apps', 'min_supported_version')) {
                $table->string('min_supported_version')->default('1.0.0')->after('current_version');
            }

            if (! Schema::hasColumn('apps', 'latest_version')) {
                $table->string('latest_version')->default('1.0.0')->after('min_supported_version');
            }

            if (! Schema::hasColumn('apps', 'force_update')) {
                $table->boolean('force_update')->default(false)->after('latest_version');
            }

            if (! Schema::hasColumn('apps', 'maintenance_mode')) {
                $table->boolean('maintenance_mode')->default(false)->after('force_update');
            }
        });
    }
};
