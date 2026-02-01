<?php

namespace App\Http\Controllers;

use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->where('is_active', true)->get();
        return ApiResponse::success(CategoryResource::collection($categories));
    }
}
