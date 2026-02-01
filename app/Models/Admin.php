<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use SoftDeletes;

    protected $guarded = [];
    protected $hidden = [
        'password',
        'is_active',
    ];
    protected $appends = ['image'];

    // Accessors
    public function getImageAttribute()
    {
        return 'https://ui-avatars.com/api/?name=' . $this->name;
    }
}
