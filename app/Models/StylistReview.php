<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StylistReview extends Model
{
    //Relations
    public function stylist()
    {
        return $this->belongsTo(Stylist::class);
    }
}
