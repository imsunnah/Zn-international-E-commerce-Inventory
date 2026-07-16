<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PcBuilderCategory extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'sort_order',
        'is_required',
        'group',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function pcBuilderProducts()
    {
        return $this->hasMany(PcBuilderProduct::class);
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            PcBuilderProduct::class,
            'pc_builder_category_id',
            'id',
            'id',
            'product_id'
        );
    }
}
