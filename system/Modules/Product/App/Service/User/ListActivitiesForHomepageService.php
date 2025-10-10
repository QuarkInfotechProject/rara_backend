<?php

namespace Modules\Product\App\Service\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\App\Models\Product;

class ListActivitiesForHomepageService
{
    public function getActivitiesProducts()
    {
        try {
            $query = $this->getBaseQuery();
            $query->where('type', 'activities');

            $products = $query->get();

            return $this->transformProducts($products);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private function getBaseQuery(): Builder
    {
        $query = Product::query()
            ->select([
                'products.id',
                'products.name',
                'products.slug',
                'products.type',
                'products.short_description',
            ])
            ->where('products.status', 'published')
            ->where('products.is_occupied', false)
            ->where('products.display_homepage', true)
            ->orderByRaw('CAST(products.display_order AS SIGNED) ASC')
            ->with([
                'tags' => function ($query) {
                    $query->select('tags.id', 'tags.name', 'tags.slug')
                        ->withPivot('product_id', 'tag_id');
                },
                'prices' => function ($query) {
                    $query->select('product_id', 'number_of_people', 'original_price_usd', 'discounted_price_usd')
                        ->orderBy('number_of_people', 'asc');
                },
                'overview:id,product_id,duration,trip_grade,max_altitude,group_size,best_time,starts',
                'ratingReviews' => function ($query) {
                    $query->select('product_id', 'overall_rating')
                        ->where('approved', true);
                }
            ]);

        $user = Auth::guard('user')->user();
        if ($user) {
            $userId = $user->id;
            $query->leftJoin('saved_products', function ($join) use ($userId) {
                $join->on('products.id', '=', 'saved_products.product_id')
                    ->where('saved_products.user_id', '=', $userId);
            })
                ->addSelect(DB::raw('CASE WHEN saved_products.id IS NOT NULL THEN TRUE ELSE FALSE END AS wishlist'));
        } else {
            $query->addSelect(DB::raw('FALSE AS wishlist'));
        }

        return $query;
    }

    private function transformProducts($products)
    {
        return $products->map(function ($product) {
            $product->featuredImage = $this->getMediaFiles($product, 'featuredImage');
            $product->featuredImages = $this->getMediaFiles($product, 'featuredImages', true);

            $ratings = $product->ratingReviews->pluck('overall_rating')->map(fn($r) => (float) $r);
            $product->average_rating = $ratings->count() > 0 ? round($ratings->avg(), 1) : null;
            $product->total_rating = $ratings->count(); // total number of reviews

            unset($product->ratingReviews);

            $product->tags = $product->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'pivot' => $tag->pivot ? $tag->pivot->toArray() : null,
                ];
            })->values()->toArray();

            $product->prices = $product->prices->map(function ($price) {
                return [
                    'product_id' => $price->product_id,
                    'number_of_people' => $price->number_of_people,
                    'original_price_usd' => $price->original_price_usd,
                    'discounted_price_usd' => $price->discounted_price_usd,
                ];
            })->values()->toArray();

            $product->overview = $product->overview ? [
                'duration' => $product->overview->duration,
                'trip_grade' => $product->overview->trip_grade,
                'max_altitude' => $product->overview->max_altitude,
                'group_size' => $product->overview->group_size,
                'best_time' => $product->overview->best_time,
                'starts' => $product->overview->starts,
            ] : null;

            $product->slug = '/activities/' . $product->slug;

            return $product;
        });
    }

    private function getMediaFiles($post, $type, $multiple = false)
    {
        $baseImageFiles = $post->filterFiles($type)->get();

        if ($multiple) {
            return $baseImageFiles->map(function ($file) {
                return [
                    'id' => $file->id,
                    'url' => $file->path . '/' . $file->temp_filename,
                ];
            })->toArray();
        } else {
            $file = $baseImageFiles->first();
            return $file ? [
                'id' => $file->id,
                'url' => $file->path . '/' . $file->temp_filename,
            ] : null;
        }
    }

}
