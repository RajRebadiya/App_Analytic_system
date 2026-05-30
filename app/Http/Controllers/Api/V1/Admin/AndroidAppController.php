<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AndroidAppRequest;
use App\Http\Resources\AndroidAppResource;
use App\Models\AndroidApp;
use App\Repositories\Eloquent\AndroidAppRepository;
use App\Services\AppProvisioningService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AndroidAppController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly AndroidAppRepository $repository,
        private readonly AppProvisioningService $provisioning,
    ) {}

    public function index(Request $request): JsonResponse
    {
        return $this->success('Apps fetched', AndroidAppResource::collection($this->repository->paginate((int) $request->integer('per_page', 25))));
    }

    public function store(AndroidAppRequest $request): JsonResponse
    {
        return $this->success('App created', new AndroidAppResource($this->repository->create($this->provisioning->createPayload($request->validated()))), 201);
    }

    public function show(AndroidApp $app): JsonResponse
    {
        return $this->success('App fetched', new AndroidAppResource($app));
    }

    public function update(AndroidAppRequest $request, AndroidApp $app): JsonResponse
    {
        return $this->success('App updated', new AndroidAppResource($this->repository->update($app, $this->provisioning->updatePayload($request->validated()))));
    }

    public function destroy(AndroidApp $app): JsonResponse
    {
        $this->repository->delete($app);

        return $this->success('App deleted');
    }
}
