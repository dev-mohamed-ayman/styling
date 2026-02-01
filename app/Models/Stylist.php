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
}
