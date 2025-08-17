<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\App\Models\User;

class SavedProduct extends Model
{

    protected $fillable = ['user_id', 'product_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
