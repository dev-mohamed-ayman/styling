<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StylistFeature extends Model
{
    protected $fillable = [
        'stylist_id',
        'icon',
        'title',
    ];

    public function stylist()
    {
        return $this->belongsTo(Stylist::class);
    }
}
