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
        'duration',
        'location',
        'max_altitude',
        'activities',
        'accommodation',
        'meal',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
