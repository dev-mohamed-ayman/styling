<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantItem extends Model
{
    use SoftDeletes;

    // Relations
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function children()
    {
        return $this->belongsToMany(
            self::class,
            'product_variant_item_relations',
            'parent_item_id',
            'child_item_id'
        );
    }

    public function parents()
    {
        return $this->belongsToMany(
            self::class,
            'product_variant_item_relations',
            'child_item_id',
            'parent_item_id'
        );
    }
}
