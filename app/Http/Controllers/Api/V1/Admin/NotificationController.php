<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\PushNotification;
use App\Repositories\Eloquent\NotificationRepository;
use App\Services\NotificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly NotificationRepository $repository,
        private readonly NotificationService $service,
    ) {}

    public function index(Request $request): JsonResponse
    {
        return $this->success('Notifications fetched', NotificationResource::collection($this->repository->paginate((int) $request->integer('per_page', 25))));
    }

    public function store(NotificationRequest $request): JsonResponse
    {
        $notification = $this->repository->create($request->validated() + ['created_by' => $request->user()?->id]);

        return $this->success('Notification created', new NotificationResource($notification), 201);
    }

    public function show(PushNotification $notification): JsonResponse
    {
        return $this->success('Notification fetched', new NotificationResource($notification));
    }

    public function update(NotificationRequest $request, PushNotification $notification): JsonResponse
    {
        return $this->success('Notification updated', new NotificationResource($this->repository->update($notification, $request->validated())));
    }

    public function destroy(PushNotification $notification): JsonResponse
    {
        $this->repository->delete($notification);

        return $this->success('Notification deleted');
    }

    public function send(PushNotification $notification): JsonResponse
    {
        $this->service->dispatch($notification);

        return $this->success('Notification queued');
    }
}
