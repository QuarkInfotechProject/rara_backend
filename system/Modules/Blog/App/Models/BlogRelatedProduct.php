<?php

namespace Modules\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\App\Models\Product;

class BlogRelatedProduct extends Model
{
    protected $fillable = [
        'blog_id',
        'product_id',
    ];

    /**
     * Get the blog that owns the BlogRelatedProduct.
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    /**
     * Get the product that is related to the blog.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
