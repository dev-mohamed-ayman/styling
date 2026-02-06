<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Stylist extends Model
{
    // Accessors
    public function image(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value
                ? asset($value)
                : "https://ui-avatars.com/api/?name=" . $this->name
        );
    }

    // Relations
    public function features()
    {
        return $this->hasMany(StylistFeature::class);
    }

    public function reviews()
    {
        return $this->hasMany(StylistReview::class);
    }

    public function fashionStyles()
    {
        return $this->belongsToMany(FashionStyle::class, 'stylist_fashion_style');
    }

    public function images()
    {
        return $this->hasMany(StylistImage::class);
    }
}
