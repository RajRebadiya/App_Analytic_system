<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Controllers\Controller;
use App\Models\AndroidApp;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdConfigController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request): JsonResponse
    {
        /** @var AndroidApp $app */
        $app = $request->attributes->get('android_app');
        $setting = $app->adNetworkSetting()->first();

        if (! $setting || ! $setting->is_active) {
            return $this->success('Ad config fetched', [
                'is_active' => false,
                'app_adShowStatus' => 0,
                'am_ad_showAdStatus' => 0,
            ]);
        }

        return $this->success('Ad config fetched', $setting->toAndroidPayload());
    }
}
