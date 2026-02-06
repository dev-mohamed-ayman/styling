<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Stylist\StylistResource;
use App\Models\Stylist;
use App\Models\StylistService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class StylistController extends Controller
{

    public function stylists(Request $request)
    {
        $stylists = Stylist::query()
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('bio', 'like', '%' . $request->search . '%')
                    ->orWhere('about', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(perPage($request->per_page));

        $stylists->setCollection(
            StylistResource::collection($stylists->getCollection())->collection
        );


        return ApiResponse::paginated($stylists);
    }

    public function stylistDetails($stylist_id)
    {
        $stylist = Stylist::query()
            ->where('id', $stylist_id)
            ->first();

        if (!$stylist) {
            return ApiResponse::error(__('stylist_not_found'));
        }

        return ApiResponse::success($stylist);
    }

    public function trusted()
    {
        $stylists = Stylist::query()
            ->orderByDesc('avg_rating')
            ->orderByDesc('reviews_count')
            ->paginate(perPage(request()->per_page));

        $stylists->setCollection(
            StylistResource::collection($stylists->getCollection())->collection
        );

        return ApiResponse::paginated($stylists);
    }

    public function recommendedServices(Request $request)
    {
        $stylestServices = StylistService::query()
            ->when(auth()->check(), function ($query) {
                $query->whereHas('stylist.fashionStyles', function ($query) {
                    $query->whereIn('fashion_styles.id', auth()->user()->fashionStyles->pluck('id'));
                });
            })
            ->latest()
            ->select('id', 'title', 'available', 'price')
            ->paginate(perPage($request->per_page));

        return ApiResponse::paginated($stylestServices);
    }
}
