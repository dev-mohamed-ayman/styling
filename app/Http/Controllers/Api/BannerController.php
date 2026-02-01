<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $banners = Banner::query()->select('id', 'image', 'link')->get();

        return ApiResponse::success($banners);
    }
}
