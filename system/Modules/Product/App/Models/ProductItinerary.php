<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductItinerary extends Model
{
    protected $fillable = [
        'product_id',
        'time_window',
        'activity',
        'order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
