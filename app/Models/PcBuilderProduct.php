<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcBuilderProduct extends Model
{
    protected $fillable = [
        'pc_builder_category_id',
        'product_id',
        'wattage',
    ];

    public function category()
    {
        return $this->belongsTo(PcBuilderCategory::class, 'pc_builder_category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
