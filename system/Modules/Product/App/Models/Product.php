<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Blog\App\Models\Blog;
use Modules\Media\Trait\HasMedia;
use Modules\Meta\Trait\HasMetaData;

class Product extends Model
{
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

        protected $fillable = [
            'type', 'slug', 'tagline', 'name', 'manager_id', 'short_description', 'description',
            'display_order', 'youtube_link',
            'latitude', 'longitude', 'location', 'average_rating', 'total_comment', 'status',
            'cancellation_policy', 'how_to_get', 'cornerstone', 'region', 'is_occupied',
            'max_occupant', 'duration', 'display_homepage', 'short_code', 'impact','category_details'
        ];

    protected $casts = [
        'has_variable_pricing' => 'boolean',
        'is_occupied' => 'boolean',
        'cornerstone' => 'boolean',
        'display_homepage' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'product_amenities');
    }

    public function included()
    {
        return $this->belongsToMany(Amenity::class, 'product_included')
            ->where('amenities.category', 'included');
    }

    public function excluded()
    {
        return $this->belongsToMany(Amenity::class, 'product_excluded')
            ->where('amenities.category', 'excluded');
    }  // apply product name filter only if provided

    public function whatToBring(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'product_what_to_bring')
            ->where('amenities.category', 'whatToBring');
    }

    public function homestayHosts(): HasMany
    {
        return $this->hasMany(ProductHomestayHost::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ProductFaq::class);
    }

    public function highlights(): HasMany
    {
        return $this->hasMany(ProductHighlight::class);
    }

    public function relatedBlogs(): BelongsToMany
    {
        return $this->belongsToMany(Blog::class, 'product_related_blogs');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function itinerary()
    {
        return $this->hasMany(ProductItinerary::class);
    }

    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_related_products', 'product_id', 'related_product_id')
            ->withPivot('relation_type')
            ->withTimestamps()
            ->using(ProductRelatedProduct::class);
    }

    public function relatedHomestaysForExperience(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_related_products', 'product_id', 'related_product_id')
            ->withPivot('relation_type')
            ->wherePivot('relation_type', 'related_homestay');
    }

    public function relatedExperiencesForHomestay(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_related_products', 'related_product_id', 'product_id')
            ->withPivot('relation_type')
            ->wherePivot('relation_type', 'related_homestay');
    }

    public function nearbyHomestays(): BelongsToMany
    {
        return $this->relatedProducts()->wherePivot('relation_type', 'nearby_homestay');
    }

    public function relatedCircuits() : BelongsToMany
    {
        return $this->relatedProducts()->wherePivot('relation_type', 'related_circuit');
    }

    public function relatedPackages() : BelongsToMany
    {
        return $this->relatedProducts()->wherePivot('relation_type', 'related_package');
    }

    public function relatedExperience() : BelongsToMany
    {
        return $this->relatedProducts()->wherePivot('relation_type', 'related_experience');
    }

    public function overview(): HasMany
    {
        return $this->hasMany(ProductOverview::class);
    }

   public function dossiers()
    {
        return $this->hasMany(Dossier::class);
    }
    public function departures()
    {
        return $this->hasMany(ProductDeparture::class);
    }

}
