<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppVersionRequest;
use App\Http\Resources\AppVersionResource;
use App\Models\AppVersion;
use App\Repositories\Eloquent\AppVersionRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppVersionController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly AppVersionRepository $repository) {}

    public function index(Request $request): JsonResponse
    {
        return $this->success('App versions fetched', AppVersionResource::collection($this->repository->paginate((int) $request->integer('per_page', 25))));
    }

    public function store(AppVersionRequest $request): JsonResponse
    {
        return $this->success('App version created', new AppVersionResource($this->repository->create($request->validated())), 201);
    }

    public function show(AppVersion $appVersion): JsonResponse
    {
        return $this->success('App version fetched', new AppVersionResource($appVersion));
    }

    public function update(AppVersionRequest $request, AppVersion $appVersion): JsonResponse
    {
        return $this->success('App version updated', new AppVersionResource($this->repository->update($appVersion, $request->validated())));
    }

    public function destroy(AppVersion $appVersion): JsonResponse
    {
        $this->repository->delete($appVersion);

        return $this->success('App version deleted');
    }
}
