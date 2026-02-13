<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class StylistImage extends Model
{
    protected $fillable = [
        'stylist_id',
        'path',
    ];

    public function path(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value
                ? asset('storage/' . $value)
                : "https://ui-avatars.com/api/?name=" . $this->stylist?->name
        );
    }

    public function stylist()
    {
        return $this->belongsTo(Stylist::class);
    }
}
