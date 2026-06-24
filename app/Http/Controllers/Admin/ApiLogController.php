<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApiLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ApiLog::query()
            ->when($request->status_code, fn ($query, int $status) => $query->where('status_code', $status))
            ->when($request->search, fn ($query, string $search) => $query->where(function ($query) use ($search) {
                $query->where('path', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate($request->integer('per_page', 10))
            ->withQueryString();

        return view('admin.api-logs.index', compact('logs'));
    }
}
