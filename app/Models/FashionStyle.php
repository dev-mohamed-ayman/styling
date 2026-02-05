<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FashionStyle extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class, '	user_fashion_style');
    }
}
