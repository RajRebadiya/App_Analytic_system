<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdvertisementRequest;
use App\Models\Advertisement;
use App\Models\AndroidApp;
use App\Services\Admin\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    public function __construct(private readonly FileUploadService $uploads) {}

    public function index(Request $request): View
    {
        $advertisements = Advertisement::query()->with('app')->when($request->app_id, fn ($query, int $appId) => $query->where('app_id', $appId))->latest()->paginate(15)->withQueryString();

        return view('admin.advertisements.index', ['advertisements' => $advertisements, 'apps' => AndroidApp::query()->orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.advertisements.form', ['advertisement' => new Advertisement, 'apps' => AndroidApp::query()->orderBy('name')->get()]);
    }

    public function store(AdvertisementRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['image'] = $this->uploads->image($request->file('image_file'), 'advertisements') ?? ($data['image'] ?? null);
        unset($data['image_file']);
        Advertisement::query()->create($data);

        return redirect()->route('admin.advertisements.index')->with('status', 'Advertisement created.');
    }

    public function edit(Advertisement $advertisement): View
    {
        return view('admin.advertisements.form', ['advertisement' => $advertisement, 'apps' => AndroidApp::query()->orderBy('name')->get()]);
    }

    public function update(AdvertisementRequest $request, Advertisement $advertisement): RedirectResponse
    {
        $data = $request->validated();
        $data['image'] = $this->uploads->image($request->file('image_file'), 'advertisements') ?? ($data['image'] ?? $advertisement->image);
        unset($data['image_file']);
        $advertisement->update($data);

        return redirect()->route('admin.advertisements.index')->with('status', 'Advertisement updated.');
    }

    public function destroy(Advertisement $advertisement): RedirectResponse
    {
        $advertisement->delete();

        return back()->with('status', 'Advertisement deleted.');
    }
}
