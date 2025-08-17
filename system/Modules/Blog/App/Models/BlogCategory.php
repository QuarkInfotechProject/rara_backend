<?php

namespace Modules\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Meta\Trait\HasMetaData;

class BlogCategory extends Model
{
    use HasFactory, HasMetaData;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'slug', 'description'];

    public static function boot()
    {
        parent::boot();

        static::saved(function ($entity) {
            $entity->saveMetaData(request('meta', []));
        });
    }

//    public function categories()
//    {
//        return $this->belongsToMany(PostCategory::class, 'post_categories');
//    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

}
