<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_network_settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('ad_network_settings', 'native_count')) {
                $table->unsignedInteger('native_count')->default(1)->nullable()->after('inner_click_count');
            }
            if (! Schema::hasColumn('ad_network_settings', 'dialog_show')) {
                $table->boolean('dialog_show')->default(false)->after('dialog_before_ad_show');
            }
            if (! Schema::hasColumn('ad_network_settings', 'affiliate_weburl1')) {
                $table->text('affiliate_weburl1')->nullable()->after('others');
            }
            if (! Schema::hasColumn('ad_network_settings', 'affiliate_weburl2')) {
                $table->text('affiliate_weburl2')->nullable()->after('affiliate_weburl1');
            }
            if (! Schema::hasColumn('ad_network_settings', 'affiliate_weburl3')) {
                $table->text('affiliate_weburl3')->nullable()->after('affiliate_weburl2');
            }
            if (! Schema::hasColumn('ad_network_settings', 'affiliate_weburl4')) {
                $table->text('affiliate_weburl4')->nullable()->after('affiliate_weburl3');
            }
            if (! Schema::hasColumn('ad_network_settings', 'affiliate_img_list')) {
                $table->text('affiliate_img_list')->nullable()->after('affiliate_weburl4');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ad_network_settings', function (Blueprint $table): void {
            $columnsToDrop = array_filter([
                'native_count',
                'dialog_show',
                'affiliate_weburl1',
                'affiliate_weburl2',
                'affiliate_weburl3',
                'affiliate_weburl4',
                'affiliate_img_list',
            ], fn ($col) => Schema::hasColumn('ad_network_settings', $col));

            if (! empty($columnsToDrop)) {
                $table->dropColumn(array_values($columnsToDrop));
            }
        });
    }
};
