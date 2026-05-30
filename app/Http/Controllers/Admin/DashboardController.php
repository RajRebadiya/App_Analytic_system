<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly AdminDashboardService $dashboard) {}

    public function __invoke(Request $request): View
    {
        return view('admin.dashboard.index', $this->dashboard->data($request->only(['app_id', 'from', 'to'])));
    }
}
