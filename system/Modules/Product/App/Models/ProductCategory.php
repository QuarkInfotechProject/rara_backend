<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'product_categories';

    protected $fillable = [
        'category_name',
        'slug',
        'description',
        'status',
        'meta_title',
        'meta_description',
        'keywords',
    ];
    protected $casts = [
        'keywords' => 'array',
    ];
    protected $dates = ['deleted_at'];

}
