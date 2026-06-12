<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppVersionRequest;
use App\Models\AndroidApp;
use App\Models\AppVersion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppVersionController extends Controller
{
    public function index(Request $request): View
    {
        $versions = AppVersion::query()
            ->with('app')
            ->when($request->app_id, fn ($query, int $appId) => $query->where('app_id', $appId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.versions.index', ['versions' => $versions, 'apps' => AndroidApp::query()->orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.versions.form', ['version' => new AppVersion, 'apps' => AndroidApp::query()->orderBy('name')->get()]);
    }

    public function store(AppVersionRequest $request): RedirectResponse
    {
        AppVersion::query()->create($request->validated());

        return redirect()->route('admin.versions.index')->with('status', 'Version created.');
    }

    public function edit(AppVersion $version): View
    {
        return view('admin.versions.form', ['version' => $version, 'apps' => AndroidApp::query()->orderBy('name')->get()]);
    }

    public function update(AppVersionRequest $request, AppVersion $version): RedirectResponse
    {
        $version->update($request->validated());

        return redirect()->route('admin.versions.index')->with('status', 'Version updated.');
    }

    public function destroy(AppVersion $version): RedirectResponse
    {
        $version->delete();

        return back()->with('status', 'Version deleted.');
    }
}
