<?php

namespace Modules\Product\App\Service\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\App\Models\Product;

class ListPopularProductForHomepageService
{
    public function getPaginatedProducts($type)
    {
        try {
            $query = $this->getBaseQuery();
            $query->where('type', $type);
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
                'products.created_at',
                'products.updated_at',
            ])
            ->where('products.status', 'published')
            ->where('products.is_occupied', false)
            ->where('products.display_homepage', true)
            ->orderByRaw('CAST(products.display_order AS SIGNED) ASC')
//            ->with(['tags' => function($query) {
//                $query->select('tags.id', 'tags.name', 'tags.description', 'display_order', 'zoom_level', 'tags.slug', 'tags.latitude', 'tags.longitude');
//            }])
        ->with([
                'prices' => function($query) {
                    $query->select('product_id', 'number_of_people', 'original_price_usd', 'discounted_price_usd')
                        ->orderBy('number_of_people', 'asc');
                },
                'overview',
                'departures'
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
//            $product->featuredImages = $this->getMediaFiles($product, 'featuredImages', true);
//            $product->tags = $product->tags->map(function ($tag) {
//                return [
//                    'id' => $tag->id,
//                    'name' => $tag->name,
//                    'slug' => $tag->slug,
//                ];
//            })->values()->toArray();
            $product->prices = $product->prices->map(function ($price) {
                return [
                    'number_of_people' => $price->number_of_people,
                    'original_price_usd' => $price->original_price_usd,
                    'discounted_price_usd' => $price->discounted_price_usd,
                ];
            })->values()->toArray();

            if ($product->overview) {
                $product->overview = [
                    'name' => $product->overview->name,
                    'description' => $product->overview->description,
                    'duration' => $product->overview->duration,
//                    'overview_location' => $product->overview->overview_location,
                    'trip_grade' => $product->overview->trip_grade,
//                    'max_altitude' => $product->overview->max_altitude,
                    'group_size' => $product->overview->group_size,
//                    'activities' => $product->overview->activities,
//                    'best_time' => $product->overview->best_time,
                    'starts' => $product->overview->starts,
                ];
            } else {
                $product->overview = [];
            }

            // Add departures
            $product->departures = $product->departures->map(function ($departure) {
                return [
                    'id' => $departure->id,
                    'departure_from' => $departure->departure_from,
                    'departure_to' => $departure->departure_to,
                    'departure_per_price' => $departure->departure_per_price,
                    'max_team_members' => $departure->max_team_members,
                ];
            })->values()->toArray();

            return $product;
        });
    }

    private function getMediaFiles($product, $type, $multiple = false)
    {
        $baseImageFiles = $product->filterFiles($type)->get();

        if ($multiple) {
            return $baseImageFiles->map(function ($file) {
                return $file->path . '/' . $file->temp_filename;
            })->toArray();
        } else {
            $baseImage = $baseImageFiles->map(function ($file) {
                return $file->path . '/' . $file->temp_filename;
            })->first();

            return $baseImage ?? '';
        }
    }

}
