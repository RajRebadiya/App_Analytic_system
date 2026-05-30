<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdNetworkSettingRequest;
use App\Http\Resources\AdNetworkSettingResource;
use App\Models\AdNetworkSetting;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            ['app_id' => $request->integer('app_id')],
            $this->payload($request),
        );

        return $this->success('Ad settings saved', new AdNetworkSettingResource($setting), 201);
    }

    public function show(AdNetworkSetting $adSetting): JsonResponse
    {
        return $this->success('Ad settings fetched', new AdNetworkSettingResource($adSetting));
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
        return $request->validated() + [
            'is_active' => false,
            'ad_show_status' => false,
            'admob_status' => false,
            'dialog_before_ad_show' => false,
            'need_internet' => false,
            'redirect_other_app_status' => false,
            'update_dialog_status' => false,
        ];
    }
}
