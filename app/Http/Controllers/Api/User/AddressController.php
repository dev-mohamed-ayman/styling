<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Address\StoreRequest;
use App\Http\Resources\Api\User\Address\AddressesResource;
use App\Models\Address;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = user()->addresses()->latest()->get();
        return ApiResponse::success(AddressesResource::collection($addresses));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        if ($request->is_primary) {
            user()->addresses()->update(['is_primary' => false]);
        }
        $address = user()->addresses()->create($data);
        return ApiResponse::success(new AddressesResource($address));
    }

    public function update(StoreRequest $request, $id)
    {
        $data = $request->validated();

        if ($request->is_primary) {
            user()->addresses()->update(['is_primary' => false]);
        }

        $address = user()->addresses()->findOrFail($id)->update($data);
        return ApiResponse::success(new AddressesResource($address));
    }

    public function destroy($id)
    {
        user()->addresses()->findOrFail($id)->delete();
        return ApiResponse::success();
    }
}
