<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;

    protected $table = 'product_price';

    protected $fillable = [
        'product_id',
        'number_of_people',
        'original_price_usd',
        'discounted_price_usd',
    ];

    protected $casts = [
        'number_of_people' => 'integer',
        'original_price_usd' => 'decimal:2',
        'discounted_price_usd' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
