<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AndroidAppResource;
use App\Models\AndroidApp;
use App\Repositories\Eloquent\AndroidAppRepository;
use App\Services\AppManagementService;
use App\Services\AppProvisioningService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UniversalController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly AndroidAppRepository $apps,
        private readonly AppProvisioningService $provisioning,
        private readonly AppManagementService $appManagement,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $base = Validator::make($request->all(), [
            'module' => ['required', 'string'],
            'action' => ['required', 'string'],
            'id' => ['nullable', 'integer'],
            'payload' => ['nullable', 'array'],
        ], [
            'module.required' => 'Module is required. Example: apps.',
            'action.required' => 'Action is required. Available actions: list, create, show, update, delete, install, heartbeat, save-token.',
            'id.integer' => 'Id must be a valid number.',
            'payload.array' => 'Payload must be a valid JSON object.',
        ])->validate();

        $module = strtolower($base['module']);
        $action = strtolower($base['action']);
        $payload = $base['payload'] ?? [];

        return match ($module) {
            'apps', 'admin_apps', 'admin-apps' => $this->handleApps($request, $action, $payload, $base['id'] ?? null),
            default => $this->error('Unsupported universal module', 422, [
                'module' => ['Supported module: apps'],
            ]),
        };
    }

    private function handleApps(Request $request, string $action, array $payload, ?int $id): JsonResponse
    {
        return match ($action) {
            'list', 'index' => $this->listApps($request),
            'create', 'store' => $this->createApp($payload),
            'show', 'get' => $this->showApp($payload, $id),
            'update' => $this->updateApp($payload, $id),
            'delete', 'destroy' => $this->deleteApp($payload, $id),
            'install' => $this->install($request, $payload),
            'heartbeat' => $this->heartbeat($request, $payload),
            'save-token', 'save_fcm', 'save-fcm', 'save_fcm_token', 'save-fcm-token' => $this->saveToken($request, $payload),
            default => $this->error('Unsupported universal action', 422, [
                'action' => ['Supported actions: list, create, show, update, delete, install, heartbeat, save-token'],
            ]),
        };
    }

    private function listApps(Request $request): JsonResponse
    {
        return $this->success(
            'Apps fetched',
            AndroidAppResource::collection($this->apps->paginate((int) $request->integer('per_page', 25))),
        );
    }

    private function createApp(array $payload): JsonResponse
    {
        $data = Validator::make($payload, $this->appRules(), $this->validationMessages())->validate();

        return $this->success(
            'App created',
            new AndroidAppResource($this->apps->create($this->provisioning->createPayload($data))),
            201,
        );
    }

    private function showApp(array $payload, ?int $id): JsonResponse
    {
        return $this->success('App fetched', new AndroidAppResource($this->findApp($payload, $id)));
    }

    private function updateApp(array $payload, ?int $id): JsonResponse
    {
        $app = $this->findApp($payload, $id);
        $data = Validator::make($payload, $this->appRules($app->id), $this->validationMessages())->validate();

        return $this->success(
            'App updated',
            new AndroidAppResource($this->apps->update($app, $this->provisioning->updatePayload($data))),
        );
    }

    private function deleteApp(array $payload, ?int $id): JsonResponse
    {
        $this->apps->delete($this->findApp($payload, $id));

        return $this->success('App deleted');
    }

    private function install(Request $request, array $payload): JsonResponse
    {
        $data = Validator::make($payload, [
            'device_id' => ['required', 'string', 'max:128'],
            'device_name' => ['nullable', 'string', 'max:255'],
            'device_brand' => ['nullable', 'string', 'max:255'],
            'android_version' => ['nullable', 'string', 'max:32'],
            'app_version' => ['required', 'string', 'max:32'],
        ], $this->validationMessages())->validate();
        $app = $this->authenticatedApp($request, $payload);

        return $this->success('Installation tracked', $this->appManagement->trackInstall($app, $data, $request->ip()));
    }

    private function heartbeat(Request $request, array $payload): JsonResponse
    {
        $data = Validator::make($payload, [
            'device_id' => ['required', 'string', 'max:128'],
            'app_version' => ['nullable', 'string', 'max:32'],
        ], $this->validationMessages())->validate();
        $app = $this->authenticatedApp($request, $payload);

        return $this->success('Heartbeat tracked', $this->appManagement->heartbeat($app, $data));
    }

    private function saveToken(Request $request, array $payload): JsonResponse
    {
        $data = Validator::make($payload, [
            'device_id' => ['required', 'string', 'max:128'],
            'fcm_token' => ['required', 'string', 'max:4096'],
            'is_active' => ['sometimes', 'boolean'],
        ], $this->validationMessages())->validate();
        $app = $this->authenticatedApp($request, $payload);

        return $this->success('Device token saved', $this->appManagement->saveToken($app, $data));
    }

    private function appRules(?int $ignoreAppId = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'package_name' => ['required', 'string', 'max:255', Rule::unique('apps', 'package_name')->ignore($ignoreAppId)],
        ];
    }

    private function findApp(array $payload, ?int $id): AndroidApp
    {
        $appId = $id ?? $payload['id'] ?? null;

        if (! $appId) {
            throw ValidationException::withMessages(['id' => ['Id is required for this action. Send id at root or payload.id.']]);
        }

        $app = AndroidApp::query()->find($appId);

        if (! $app) {
            abort(response()->json([
                'status_code' => 404,
                'message' => "App not found for id {$appId}",
            ], 404));
        }

        return $app;
    }

    private function authenticatedApp(Request $request, array $payload): AndroidApp
    {
        $packageName = $request->header(
            'app_package_name',
            $request->header('X-App-Package-Name', $payload['app_package_name'] ?? $payload['package_name'] ?? $request->input('app_package_name', $request->input('package_name')))
        );

        Validator::make([
            'app_package_name' => $packageName,
        ], [
            'app_package_name' => ['required', 'string'],
        ], [
            'app_package_name.required' => 'App package name is required. Send app_package_name header or payload.app_package_name.',
        ])->validate();

        $app = AndroidApp::query()
            ->where('package_name', $packageName)
            ->first();

        if (! $app || $app->status !== 'active') {
            abort(response()->json(['status_code' => 401, 'message' => 'Invalid app package name'], 401));
        }

        return $app;
    }

    private function validationMessages(): array
    {
        return [
            'device_id.required' => 'Device id is required.',
            'device_id.max' => 'Device id may not be greater than 128 characters.',
            'app_version.required' => 'App version is required.',
            'app_version.max' => 'App version may not be greater than 32 characters.',
            'fcm_token.required' => 'FCM token is required.',
            'fcm_token.max' => 'FCM token may not be greater than 4096 characters.',
            'name.required' => 'App name is required.',
            'package_name.required' => 'Package name is required.',
        ];
    }
}
