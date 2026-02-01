<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    public function image(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value
                ? asset($value)
                : 'https://ui-avatars.com/api/?name=' . $this->name
        );
    }
}
