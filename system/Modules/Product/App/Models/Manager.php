<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'description',
        'email',
        'phone_number',
        'status'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
