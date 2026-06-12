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
        $setting = $app->adNetworkSetting()->with('app')->first();

        if (! $setting) {
            return $this->error('Ad config not found', 404);
        }

        return $this->success('Ad config fetched', [$setting->toAndroidPayload()]);
    }
}
