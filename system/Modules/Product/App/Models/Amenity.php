<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Amenity extends Model
{
    use HasFactory;

    CONST TYPE_AMENITY = 'amenity';
    CONST TYPE_INCLUDED = 'included';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'icon',
        'description',
        'category'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_amenities');
    }

    public function includedInProducts()
    {
        return $this->belongsToMany(Product::class, 'product_included');
    }

}
