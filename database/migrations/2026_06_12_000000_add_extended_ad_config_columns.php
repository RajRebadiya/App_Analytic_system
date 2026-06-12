<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_network_settings', function (Blueprint $table): void {
            $table->string('admob_medium_rectangle_id')->nullable()->after('admob_banner_id');
            $table->string('admob_app_open_id')->nullable()->after('admob_rewarded_id');
            $table->string('adx_inter_id')->nullable()->after('admob_app_open_id');
            $table->string('adx_banner_id')->nullable()->after('adx_inter_id');
            $table->string('adx_medium_rectangle_id')->nullable()->after('adx_banner_id');
            $table->string('adx_native_id')->nullable()->after('adx_medium_rectangle_id');
            $table->string('adx_app_open_id')->nullable()->after('adx_native_id');
            $table->string('fb_inter_id')->nullable()->after('adx_app_open_id');
            $table->string('fb_banner_id')->nullable()->after('fb_inter_id');
            $table->string('fb_medium_rectangle_id')->nullable()->after('fb_banner_id');
            $table->string('fb_native_id')->nullable()->after('fb_medium_rectangle_id');
            $table->string('fb_native_banner_id')->nullable()->after('fb_native_id');
            $table->string('wortise_app_id')->nullable()->after('fb_native_banner_id');
            $table->string('wortise_app_open_id')->nullable()->after('wortise_app_id');
            $table->string('wortise_inter_id')->nullable()->after('wortise_app_open_id');
            $table->string('wortise_banner_id')->nullable()->after('wortise_inter_id');
            $table->string('wortise_medium_rectangle_id')->nullable()->after('wortise_banner_id');
            $table->string('wortise_native_id')->nullable()->after('wortise_medium_rectangle_id');
            $table->string('ad_splash')->default('splash_appopen')->after('inner_click_count');
            $table->string('ad_inter')->default('admob')->after('ad_splash');
            $table->string('ad_appopen')->default('appopen')->after('ad_inter');
            $table->string('ad_native')->default('admob')->after('ad_appopen');
            $table->string('ad_small_native')->default('admob')->after('ad_native');
            $table->string('ad_banner')->default('admob')->after('ad_small_native');
            $table->string('ad_qureka')->default('off')->after('ad_banner');
            $table->string('new_app_name')->nullable()->after('new_package_name');
            $table->string('new_app_icon')->nullable()->after('new_app_name');
            $table->string('new_app_banner')->nullable()->after('new_app_icon');
            $table->text('new_app_body')->nullable()->after('new_app_banner');
            $table->string('new_app_link')->nullable()->after('new_app_body');
            $table->string('download_status')->default('off')->after('new_app_link');
            $table->string('background_status')->default('off')->after('download_status');
            $table->string('popup_status')->default('off')->after('background_status');
            $table->string('main_click_status')->default('on')->after('popup_status');
        });
    }

    public function down(): void
    {
        Schema::table('ad_network_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'admob_medium_rectangle_id',
                'admob_app_open_id',
                'adx_inter_id',
                'adx_banner_id',
                'adx_medium_rectangle_id',
                'adx_native_id',
                'adx_app_open_id',
                'fb_inter_id',
                'fb_banner_id',
                'fb_medium_rectangle_id',
                'fb_native_id',
                'fb_native_banner_id',
                'wortise_app_id',
                'wortise_app_open_id',
                'wortise_inter_id',
                'wortise_banner_id',
                'wortise_medium_rectangle_id',
                'wortise_native_id',
                'ad_splash',
                'ad_inter',
                'ad_appopen',
                'ad_native',
                'ad_small_native',
                'ad_banner',
                'ad_qureka',
                'new_app_name',
                'new_app_icon',
                'new_app_banner',
                'new_app_body',
                'new_app_link',
                'download_status',
                'background_status',
                'popup_status',
                'main_click_status',
            ]);
        });
    }
};
