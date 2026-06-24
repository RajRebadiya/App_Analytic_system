<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AndroidAppRequest;
use App\Models\AndroidApp;
use App\Services\AppProvisioningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AppController extends Controller
{
    public function __construct(private readonly AppProvisioningService $provisioning) {}

    public function index(Request $request): View
    {
        $apps = AndroidApp::query()
            ->when($request->search, fn ($query, string $search) => $query->where('name', 'like', "%{$search}%")->orWhere('package_name', 'like', "%{$search}%"))
            ->latest()
            ->paginate($request->integer('per_page', 10))
            ->withQueryString();

        return view('admin.apps.index', compact('apps'));
    }

    public function create(): View
    {
        return view('admin.apps.form', ['app' => new AndroidApp]);
    }

    public function store(AndroidAppRequest $request): RedirectResponse
    {
        AndroidApp::query()->create($this->provisioning->createPayload($request->validated()));

        return redirect()->route('admin.apps.index')->with('status', 'App created.');
    }

    public function edit(AndroidApp $app): View
    {
        return view('admin.apps.form', compact('app'));
    }

    public function update(AndroidAppRequest $request, AndroidApp $app): RedirectResponse
    {
        $app->update($this->provisioning->updatePayload($request->validated()));

        return redirect()->route('admin.apps.index')->with('status', 'App updated.');
    }

    public function destroy(AndroidApp $app): RedirectResponse
    {
        $app->delete();

        return back()->with('status', 'App deleted.');
    }

    public function status(AndroidApp $app, string $status): RedirectResponse
    {
        abort_unless(in_array($status, ['active', 'inactive'], true), 404);
        $app->update(['status' => $status]);

        return back()->with('status', 'App status updated.');
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Package', 'Current Version', 'Status']);
            AndroidApp::query()->orderBy('name')->each(fn (AndroidApp $app) => fputcsv($handle, [$app->id, $app->name, $app->package_name, $app->current_version, $app->status]));
            fclose($handle);
        }, 'apps.csv');
    }
}
