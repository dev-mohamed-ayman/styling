<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stylist extends Model
{
    // Relations
    public function reviews()
    {
        return $this->hasMany(StylistReview::class);
    }

    public function fashionStyles()
    {
        return $this->belongsToMany(FashionStyle::class, 'stylist_fashion_style');
    }
}
