<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Trait\HasMedia;

class ProductHighlight extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'description',
        'order'
    ];

    use HasFactory, HasMedia;

    public static function boot()
    {
        parent::boot();

        static::saved(function ($entity) {
            $entity->syncFiles(request('highlightFiles', []));
        });

        static::deleting(function ($entity) {
            $entity->files()->detach();

        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
