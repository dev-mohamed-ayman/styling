<?php

namespace App\Http\Resources\Api\Stylist;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StylistResource extends JsonResource
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
            'bio' => $this->bio,
            'price' => $this->pricd,
        ];
    }
}
