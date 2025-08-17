<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\App\Models\User;

class ProductRatingReview extends Model
{

    protected $fillable = [
        'product_id', 'user_id', 'cleanliness', 'hospitality', 'value_for_money',
        'communication', 'overall_rating', 'public_review', 'private_review',
        'reply_to_public_review', 'approved',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
