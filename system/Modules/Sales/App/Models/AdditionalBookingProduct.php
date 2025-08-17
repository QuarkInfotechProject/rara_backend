<?php

namespace Modules\Sales\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\App\Models\Product;

class AdditionalBookingProduct extends Model
{

    protected $fillable = [
        'booking_id',
        'product_id',
        'name',
        'description',
    ];

    /**
     * Get the booking that owns the additional booking product.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the product associated with the additional booking product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
