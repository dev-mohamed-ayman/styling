<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stylist extends Model
{
    protected $fillable = [
        'name',
        'image',
        'cover',
        'bio',
        'avg_rating',
        'reviews_count',
        'about',
        'price'
    ];

    protected $appends = ['image_url', 'cover_url'];

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : "https://ui-avatars.com/api/?name=" . $this->name;
    }

    public function getCoverUrlAttribute()
    {
        return $this->cover
            ? asset('storage/' . $this->cover)
            : null;
    }

    public function fashionStyles()
    {
        return $this->belongsToMany(FashionStyle::class, 'stylist_fashion_style');
    }



    public function images()
    {
        return $this->hasMany(StylistImage::class);
    }

    public function services()
    {
        return $this->hasMany(StylistService::class);
    }

    public function reviews()
    {
        return $this->hasMany(StylistReview::class);
    }
}
