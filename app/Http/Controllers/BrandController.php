<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->where('is_active', true)->select('name', 'image')->get();
        return ApiResponse::success($brands);
    }
}
