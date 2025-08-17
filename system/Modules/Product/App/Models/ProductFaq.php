<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFaq extends Model
{

    protected $fillable = [
        'product_id',
        'question',
        'answer',
        'order'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
