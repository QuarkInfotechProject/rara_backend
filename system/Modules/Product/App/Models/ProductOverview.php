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
        'duration',
        'overview_location',
        'trip_grade',
        'max_altitude',
        'group_size',
        'activities',
        'best_time',
        'starts',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
