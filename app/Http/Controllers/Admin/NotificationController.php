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
            ->paginate(15)
            ->withQueryString();

        return view('admin.notifications.index', ['notifications' => $notifications, 'apps' => AndroidApp::query()->orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.notifications.form', ['notification' => new PushNotification, 'apps' => AndroidApp::query()->orderBy('name')->get()]);
    }

    public function store(NotificationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['image'] = $this->uploads->image($request->file('image_file'), 'notifications') ?? ($data['image'] ?? null);
        $data['created_by'] = $request->user()->id;
        unset($data['image_file'], $data['send_now']);
        $notification = PushNotification::query()->create($data);

        if ($request->boolean('send_now')) {
            $this->notifications->send($notification);
        }

        return redirect()->route('admin.notifications.index')->with('status', $request->boolean('send_now') ? 'Notification created and queued.' : 'Notification created.');
    }

    public function edit(PushNotification $notification): View
    {
        return view('admin.notifications.form', ['notification' => $notification, 'apps' => AndroidApp::query()->orderBy('name')->get()]);
    }

    public function update(NotificationRequest $request, PushNotification $notification): RedirectResponse
    {
        $data = $request->validated();
        $data['image'] = $this->uploads->image($request->file('image_file'), 'notifications') ?? ($data['image'] ?? $notification->image);
        unset($data['image_file'], $data['send_now']);
        $notification->update($data);

        return redirect()->route('admin.notifications.index')->with('status', 'Notification updated.');
    }

    public function destroy(PushNotification $notification): RedirectResponse
    {
        $notification->delete();

        return back()->with('status', 'Notification deleted.');
    }

    public function send(PushNotification $notification): RedirectResponse
    {
        $this->notifications->send($notification);

        return back()->with('status', 'Notification queued for background sending.');
    }

    public function logs(PushNotification $notification): View
    {
        return view('admin.notifications.logs', ['notification' => $notification, 'logs' => $notification->logs()->latest()->paginate(25)]);
    }
}
