<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductHomestay extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class);
    }
}
