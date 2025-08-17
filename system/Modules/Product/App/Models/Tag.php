<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Trait\HasMedia;

class Tag extends Model
{

    use HasFactory, HasMedia;

    public static function boot()
    {
        parent::boot();

        static::saved(function ($entity) {
            $entity->syncFiles(request('files', []));
        });

        static::deleting(function ($entity) {
            $entity->files()->detach();
        });
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'latitude',
        'longitude',
        'display_order',
        'zoom_level'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags');
    }
}
