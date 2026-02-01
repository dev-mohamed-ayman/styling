<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::query()->latest()->get();
        return ApiResponse::success($cities);
    }
}
