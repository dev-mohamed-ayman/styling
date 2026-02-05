<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Stylist\StylistResource;
use App\Models\FashionStyle;
use App\Models\Stylist;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class FashionStyleController extends Controller
{
    public function __invoke()
    {
        $fashionStyles = FashionStyle::query()
            ->latest()
            ->select('id', 'name')
            ->paginate(perPage(request()->per_page));


        return ApiResponse::paginated($fashionStyles);
    }
}
