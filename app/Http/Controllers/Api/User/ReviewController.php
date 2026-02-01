<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function review(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|min:10'
        ]);

        ProductReview::query()->updateOrCreate([
            'user_id' => user()->id,
            'product_id' => $request->product_id
        ], [
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return ApiResponse::success();

    }
}
