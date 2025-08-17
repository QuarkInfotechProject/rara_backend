<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Trait\HasMedia;

class ProductHomestayHost extends Model
{

    protected $table = 'product_homestay_hosts';

    use HasFactory, HasMedia;

    public static function boot()
    {
        parent::boot();

        static::saved(function ($entity) {
            $entity->syncFiles(request('hostFiles', []));
        });

        static::deleting(function ($entity) {
            $entity->files()->detach();

        });
    }


    protected $fillable = [
        'product_id',
        'fullname',
        'description'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
