<?php

namespace Modules\Blog\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\AdminUser\App\Models\AdminUser;
use Modules\Media\Trait\HasMedia;
use Modules\Meta\Trait\HasMetaData;
use Modules\Product\App\Models\Product;

class Blog extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type',
        'title',
        'short_description',
        'description',
        'publish_date',
        'status',
        'read_time',
        'slug',
        'blog_category_id',
        'admin_user_id',
        'updated_at',
        'mediaName',
        'views_count',
        'display_homepage',
        'display_order'
    ];

    public CONST STATUS_DRAFT = 'draft';
    public CONST STATUS_PUBLISHED = 'published';
    public CONST STATUS_DELETED = 'deleted';

    public CONST STATUS_BLOG = 'blog';
    public CONST STATUS_MEDIA_COVERAGE = 'mediaCoverage';
    public CONST STATUS_REPORT = 'report';

    //mediaImage, featuredImage

    use HasFactory, HasMetaData, HasMedia;

    public static function boot()
    {
        parent::boot();

        static::saved(function ($entity) {
            $entity->saveMetaData(request('meta', []));
            $entity->syncFiles(request('files', []));
        });

        static::deleting(function ($entity) {
            $entity->files()->detach();

            if ($entity->meta) {
                $entity->meta->delete();
            }
        });
    }

    public function blogLogs()
    {
        return $this->hasMany(BlogLog::class);
    }

    public function relatedProducts()
    {
        return $this->hasMany(BlogRelatedProduct::class, 'blog_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_related_blogs');
    }

}
