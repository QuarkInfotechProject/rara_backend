<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductRelatedProduct extends Pivot
{
    protected $table = 'product_related_products';

    protected $fillable = ['relation_type'];
}
