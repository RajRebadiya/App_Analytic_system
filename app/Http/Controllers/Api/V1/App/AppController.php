<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\EventRequest;
use App\Http\Requests\App\HeartbeatRequest;
use App\Http\Requests\App\InstallRequest;
use App\Http\Requests\App\SaveTokenRequest;
use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\NotificationResource;
use App\Models\AndroidApp;
use App\Services\AppManagementService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly AppManagementService $service) {}

    public function install(InstallRequest $request): JsonResponse
    {
        $installation = $this->service->trackInstall($this->app($request), $request->validated(), $request->ip());

        return $this->success('Installation tracked', $installation);
    }

    public function heartbeat(HeartbeatRequest $request): JsonResponse
    {
        $installation = $this->service->heartbeat($this->app($request), $request->validated());

        if (! $installation) {
            return $this->error('Installation not found for this device.', 404);
        }

        return $this->success('Heartbeat tracked', $installation);
    }

    public function saveToken(SaveTokenRequest $request): JsonResponse
    {
        return $this->success('Device token saved', $this->service->saveToken($this->app($request), $request->validated()));
    }

    public function ads(Request $request): JsonResponse
    {
        $ads = $this->app($request)->advertisements()
            ->where('status', 'active')
            ->where(fn ($query) => $query->whereNull('start_date')->orWhere('start_date', '<=', now()))
            ->where(fn ($query) => $query->whereNull('end_date')->orWhere('end_date', '>=', now()))
            ->orderByDesc('priority')
            ->get();

        return $this->success('Advertisements fetched', AdvertisementResource::collection($ads));
    }

    public function versionCheck(Request $request): JsonResponse
    {
        $validated = $request->validate(['current_version' => ['required', 'string', 'max:32']]);

        return $this->success('Version status fetched', $this->service->versionCheck($this->app($request), $validated['current_version']));
    }

    public function event(EventRequest $request): JsonResponse
    {
        return $this->success('Event stored', $this->service->storeEvent($this->app($request), $request->validated()), 201);
    }

    public function notifications(Request $request): JsonResponse
    {
        $notifications = $this->app($request)->notifications()->where('is_active', true)->latest()->limit(50)->get();

        return $this->success('Notifications fetched', NotificationResource::collection($notifications));
    }

    private function app(Request $request): AndroidApp
    {
        return $request->attributes->get('android_app');
    }
}
