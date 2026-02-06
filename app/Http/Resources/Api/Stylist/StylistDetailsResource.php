<?php

namespace App\Http\Resources\Api\Stylist;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StylistDetailsResource extends JsonResource
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
            'image' => $this->image,
            'avg_rating' => $this->avg_rating,
            'reviews_count' => $this->reviews_count,
            'bio' => $this->bio,
            'price' => $this->pricd,
            'cover' => $this->cover,
            'about' => $this->about,
            'features' => $this->features()->select('id', 'icon', 'title')->get(),
            'images' => $this->images()->pluck('path'),
            'services' => $this->services()->select('id', 'title', 'available', 'price')->get(),
            'reviews' => $this->reviews()->select('id', 'user_id', 'comment', 'rating')->with('user:id,image,name')->get()
        ];
    }
}
