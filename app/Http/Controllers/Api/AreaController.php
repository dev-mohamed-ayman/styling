<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $areas = Area::query()
            ->when($request->city_id, fn($query) => $query->where('city_id', $request->city_id))
            ->latest()
            ->get();

        return ApiResponse::success($areas);
    }
}
