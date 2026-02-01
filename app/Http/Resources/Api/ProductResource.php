<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'offer_price' => $this->offer_price,
            'offer_expired_at' => $this->offer_expired_at,
            'discount_percentage' => $this->offer_price ? ($this->offer_price - $this->price) * $this->offer_price / 100 : 0,
            'description' => $this->description,
            'rating' => $this->withAvg('reviews', 'rating'),
            'category' => new CategoryResource($this->category),
            'attributes' => $this->resource->attributes()->get()->map(function ($attribute) {
                return [
                    'key' => $attribute->key,
                    'values' => $attribute->value
                ];
            }),
            'variants' => $this->variants()->get()->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'is_color' => $variant->is_color,
                    'items' => $variant->items()->get()->map(function ($item) use ($variant) {
                        $active = true;
                        if ($variant->items?->first()?->id != $item->product_variant_id) {
                            if (\request()->items) {
                                $active = DB::table('product_variant_item_relations')
                                    ->where('child_item_id', $item->id)
                                    ->whereIn('parent_item_id', \request()->items)
                                    ->exists();
                            }
                        }
                        return [
                            'id' => $item->id,
                            'value' => $item->value,
                            'is_active' => $active
                        ];
                    })
                ];
            }),
            'images' => $this->files()->get()->map(function ($file) {
                return [
                    'url' => asset($file->path)
                ];
            })
        ];
    }
}
