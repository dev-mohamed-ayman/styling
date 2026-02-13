<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StylistService extends Model
{
    protected $fillable = [
        'stylist_id',
        'title',
        'available',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'available' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function stylist()
    {
        return $this->belongsTo(Stylist::class);
    }
}
