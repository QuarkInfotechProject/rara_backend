<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOverview extends Model
{

    protected $fillable = [
        'product_id',
        'name',
        'description',
        'order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
