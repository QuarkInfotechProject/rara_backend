<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInclude extends Model
{

    protected $fillable = ['name', 'description'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_includes');
    }

}
