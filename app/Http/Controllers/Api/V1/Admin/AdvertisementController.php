<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdvertisementRequest;
use App\Http\Resources\AdvertisementResource;
use App\Models\Advertisement;
use App\Repositories\Eloquent\AdvertisementRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly AdvertisementRepository $repository) {}

    public function index(Request $request): JsonResponse
    {
        return $this->success('Advertisements fetched', AdvertisementResource::collection($this->repository->paginate((int) $request->integer('per_page', 25))));
    }

    public function store(AdvertisementRequest $request): JsonResponse
    {
        return $this->success('Advertisement created', new AdvertisementResource($this->repository->create($request->validated())), 201);
    }

    public function show(Advertisement $advertisement): JsonResponse
    {
        return $this->success('Advertisement fetched', new AdvertisementResource($advertisement));
    }

    public function update(AdvertisementRequest $request, Advertisement $advertisement): JsonResponse
    {
        return $this->success('Advertisement updated', new AdvertisementResource($this->repository->update($advertisement, $request->validated())));
    }

    public function destroy(Advertisement $advertisement): JsonResponse
    {
        $this->repository->delete($advertisement);

        return $this->success('Advertisement deleted');
    }
}
