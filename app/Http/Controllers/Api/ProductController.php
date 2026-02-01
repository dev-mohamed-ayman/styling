<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductResource;
use App\Models\Product;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->select('id', 'name', 'price', 'offer_price')
            ->withAvg('reviews', 'rating')
            ->paginate(perPage($request->per_page));
        return ApiResponse::paginated($products);

    }

    public function show(Request $request, $id)
    {
        $products = Product::query()->with(['category', 'variants.items', 'attributes'])->findOrFail($id);
        return ApiResponse::success(new ProductResource($products));
    }
}
