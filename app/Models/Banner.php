<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'link',
        'image'
    ];
    protected $appends = ['image_url'];
    // Accessors
    public function getImageAttribute($value)
    {
        return $value ? asset($value) : null;
    }
}
