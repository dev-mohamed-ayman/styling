<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FashionStyle extends Model
{
    protected $fillable = [
        'name',
        'image'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : "https://ui-avatars.com/api/?name=" . $this->name;
    }


    public function users()
    {
        return $this->belongsToMany(User::class, '	user_fashion_style');
    }
}
