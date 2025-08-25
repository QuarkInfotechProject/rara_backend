<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDeparture extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_id',
        'departure_from',
        'departure_to',
        'departure_per_price',
        'max_team_members',
    ];

    /**
     * Relationship with Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
