<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdNetworkSettingRequest;
use App\Http\Resources\AdNetworkSettingResource;
use App\Models\AdNetworkSetting;
use App\Models\AndroidApp;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdNetworkSettingController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $settings = AdNetworkSetting::query()
            ->with('app')
            ->latest()
            ->paginate((int) $request->integer('per_page', 25));

        return $this->success('Ad settings fetched', AdNetworkSettingResource::collection($settings));
    }

    public function store(AdNetworkSettingRequest $request): JsonResponse
    {
        $setting = AdNetworkSetting::query()->updateOrCreate(
            ['app_id' => $this->appDatabaseId($request)],
            $this->payload($request),
        );

        return $this->success('Ad settings saved', new AdNetworkSettingResource($setting), 201);
    }

    public function show(AdNetworkSetting $adSetting): JsonResponse
    {
        return $this->success('Ad settings fetched', new AdNetworkSettingResource($adSetting->loadMissing('app')));
    }

    public function update(AdNetworkSettingRequest $request, AdNetworkSetting $adSetting): JsonResponse
    {
        $adSetting->update($this->payload($request));

        return $this->success('Ad settings updated', new AdNetworkSettingResource($adSetting));
    }

    public function destroy(AdNetworkSetting $adSetting): JsonResponse
    {
        $adSetting->delete();

        return $this->success('Ad settings deleted');
    }

    private function payload(AdNetworkSettingRequest $request): array
    {
        $payload = AdNetworkSetting::normalizePayload($request->validated() + [
            'is_active' => false,
            'ad_show_status' => false,
            'admob_status' => false,
            'how_show_ad' => 0,
            'main_click_count' => 1,
            'inner_click_count' => 1,
            'dialog_before_ad_show' => false,
            'dialog_time_seconds' => 2,
            'need_internet' => false,
            'redirect_other_app_status' => false,
            'update_dialog_status' => false,
        ]);

        $payload['app_id'] = $this->appDatabaseId($request);

        return $payload;
    }

    private function appDatabaseId(AdNetworkSettingRequest $request): int
    {
        $data = $request->validated();

        if (! empty($data['app_db_id'])) {
            return (int) $data['app_db_id'];
        }

        if (! empty($data['app_package_name']) || ! empty($data['package_name'])) {
            $packageName = $data['app_package_name'] ?? $data['package_name'];
            $app = AndroidApp::query()->where('package_name', $packageName)->first();

            if ($app) {
                return $app->id;
            }
        }

        if (! empty($data['app_id']) && is_numeric($data['app_id'])) {
            return (int) $data['app_id'];
        }

        throw ValidationException::withMessages([
            'app_package_name' => ['App not found. Send app_package_name, package_name, app_db_id, or numeric app_id.'],
        ]);
    }
}
