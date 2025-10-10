<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Meta\Trait\HasMetaData;

class ProductCategory extends Model
{
    use HasFactory,SoftDeletes,HasMetaData;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'product_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function ($entity) {
            $entity->saveMetaData(request('meta', []));
        });
    }
    protected $dates = ['deleted_at'];

}
