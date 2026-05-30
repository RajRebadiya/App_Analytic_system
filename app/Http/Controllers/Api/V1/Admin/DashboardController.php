<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly AnalyticsService $analytics) {}

    public function __invoke(Request $request): JsonResponse
    {
        return $this->success('Dashboard analytics fetched', $this->analytics->dashboard($request->integer('app_id') ?: null));
    }
}
