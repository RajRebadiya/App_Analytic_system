<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NotificationRequest;
use App\Models\AndroidApp;
use App\Models\PushNotification;
use App\Services\Admin\AdminNotificationService;
use App\Services\Admin\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(
        private readonly FileUploadService $uploads,
        private readonly AdminNotificationService $notifications,
    ) {}

    public function index(Request $request): View
    {
        $notifications = PushNotification::query()
            ->with('app')
            ->withCount('logs')
            ->when($request->app_id, fn ($query, int $appId) => $query->where('app_id', $appId))
            ->latest()
            ->paginate($request->integer('per_page', 10))
            ->withQueryString();

        return view('admin.notifications.index', ['notifications' => $notifications, 'apps' => AndroidApp::query()->orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.notifications.form', [
            'notification' => new PushNotification,
            'apps' => AndroidApp::query()->orderBy('name')->get(),
        ]);
    }

    public function store(NotificationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $app = AndroidApp::query()->find($data['app_id']);

        if (! $app) {
            throw ValidationException::withMessages([
                'app_id' => ['Create at least one app before sending a push notification.'],
            ]);
        }

        $data['image'] = $this->uploads->publicImage($request->file('image_file'), 'notification') ?? ($data['image'] ?? null);
        $data['created_by'] = $request->user()?->id;
        $data['notification_type'] = $data['notification_type'] ?? 'onesignal';
        $data['send_to'] = $data['send_to'] ?? 'all';
        $data['status'] = 'pending';
        $data['app_id'] = $app->id;
        unset($data['image_file'], $data['send_now']);
        $notification = PushNotification::query()->create($data);

        if (! empty($data['scheduled_at'])) {
            return redirect()->route('admin.notifications.show', $notification)->with('status', 'Notification scheduled successfully.');
        }

        $result = $this->notifications->send($notification);

        if (! $result['successful']) {
            return redirect()
                ->route('admin.notifications.show', $notification)
                ->withErrors(['onesignal' => $this->errorMessage($result)]);
        }

        return redirect()->route('admin.notifications.show', $notification)->with('status', 'Notification sent successfully.');
    }

    public function show(PushNotification $notification): View
    {
        return view('admin.notifications.show', ['notification' => $notification->loadMissing('creator')]);
    }

    public function edit(PushNotification $notification): View
    {
        return view('admin.notifications.form', ['notification' => $notification, 'apps' => AndroidApp::query()->orderBy('name')->get()]);
    }

    public function update(NotificationRequest $request, PushNotification $notification): RedirectResponse
    {
        $data = $request->validated();
        $app = AndroidApp::query()->find($data['app_id']);

        if (! $app) {
            throw ValidationException::withMessages([
                'app_id' => ['Selected app could not be found.'],
            ]);
        }
        $data['image'] = $this->uploads->publicImage($request->file('image_file'), 'notification') ?? ($data['image'] ?? $notification->image);
        unset($data['image_file'], $data['send_now']);
        $notification->update($data);

        return redirect()->route('admin.notifications.index')->with('status', 'Notification updated.');
    }

    public function destroy(PushNotification $notification): RedirectResponse
    {
        $notification->delete();

        return back()->with('status', 'Notification deleted.');
    }

    public function toggleActive(PushNotification $notification): \Illuminate\Http\JsonResponse
    {
        $notification->update(['is_active' => !$notification->is_active]);
        return response()->json([
            'status_code' => 200,
            'message' => 'Notification status updated.',
            'data' => ['is_active' => $notification->is_active]
        ]);
    }

    public function send(PushNotification $notification): RedirectResponse
    {
        if (!$notification->is_active) {
            return back()->withErrors(['onesignal' => 'Cannot send an inactive notification. Please turn it on first.']);
        }

        $result = $this->notifications->send($notification);

        if (! $result['successful']) {
            return back()->withErrors(['onesignal' => $this->errorMessage($result)]);
        }

        return back()->with('status', 'Notification sent successfully.');
    }

    public function logs(PushNotification $notification): View
    {
        return view('admin.notifications.logs', ['notification' => $notification, 'logs' => $notification->logs()->latest()->paginate(request()->integer('per_page', 10))]);
    }

    private function errorMessage(array $result): string
    {
        $response = $result['response'] ?? [];

        if (isset($response['errors'])) {
            return is_array($response['errors']) ? implode(' ', $response['errors']) : (string) $response['errors'];
        }

        if (isset($response['error'])) {
            return (string) $response['error'];
        }

        return 'OneSignal API request failed.';
    }

}
