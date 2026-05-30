<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_network_settings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->cascadeOnDelete();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('ad_show_status')->default(true);
            $table->boolean('admob_status')->default(true);
            $table->string('admob_app_id')->nullable();
            $table->string('admob_banner_id')->nullable();
            $table->string('admob_interstitial_id')->nullable();
            $table->string('admob_native_id')->nullable();
            $table->string('admob_rewarded_id')->nullable();
            $table->unsignedTinyInteger('how_show_ad')->default(0)->comment('0=sequence, 1=alternate');
            $table->string('ad_platform_sequence')->default('Admob');
            $table->string('alternate_ad_show')->nullable();
            $table->unsignedInteger('main_click_count')->default(1);
            $table->unsignedInteger('inner_click_count')->default(1);
            $table->boolean('dialog_before_ad_show')->default(false);
            $table->unsignedInteger('dialog_time_seconds')->default(2);
            $table->boolean('need_internet')->default(false);
            $table->boolean('redirect_other_app_status')->default(false);
            $table->string('new_package_name')->nullable();
            $table->boolean('update_dialog_status')->default(false);
            $table->string('version_codes')->nullable();
            $table->string('privacy_policy_url')->nullable();
            $table->string('more_app_url')->nullable();
            $table->timestamps();
            $table->unique('app_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_network_settings');
    }
};
