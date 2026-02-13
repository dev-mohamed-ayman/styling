<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StylistReview extends Model
{
    protected $fillable = [
        'stylist_id',
        'user_id',
        'rating',
        'review',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function stylist()
    {
        return $this->belongsTo(Stylist::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
