<?php

namespace Modules\Product\App\Service\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\App\Models\Product;

class ListAdventuresForHomepageService
{
    public function getAdventuresProducts()
    {
        try {
            $today = Carbon::today();

            $baseQuery = $this->getBaseQuery();

            // Only products that have departures from today onwards
            $baseQuery->whereHas('departures', function ($q) use ($today) {
                $q->whereDate('departure_from', '>=', $today);
            });

            // Get the products
            $products = $baseQuery->get();

            if ($products->isEmpty()) {
                return collect([]);
            }

            // Load departures with the product
            $products->load(['departures' => function ($query) use ($today) {
                $query->whereDate('departure_from', '>=', $today)
                    ->select('id', 'product_id', 'departure_from', 'departure_to')
                    ->orderBy('departure_from', 'asc');
            }]);

            // Attach earliest_departure for each product
            $products = $products->map(function ($product) {
                if ($product->departures && $product->departures->isNotEmpty()) {
                    $earliestDeparture = $product->departures->first();
                    $product->earliest_departure = $earliestDeparture->departure_from;
                } else {
                    $product->earliest_departure = null;
                }
                return $product;
            });

            // Transform products (keep all)
            $transformed = $this->transformProducts($products);

            // Sort products by earliest departure (ascending)
            $sorted = collect($transformed)->sortBy(function ($product) {
                return $product->earliest_departure ? Carbon::parse($product->earliest_departure) : now()->addYears(100);
            })->values();

            return $sorted;

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
                'products.status',
                'products.is_occupied',
                'products.display_homepage',
                'products.display_order'
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
                'overview:id,product_id,duration,trip_grade,max_altitude,group_size,best_time,starts'
            ]);

        // Add wishlist join if user is logged in
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

            // Transform departures to array format
            if ($product->departures) {
                $product->departures = $product->departures->map(function ($departure) {
                    return [
                        'id' => $departure->id,
                        'from' => $departure->departure_from,
                        'to' => $departure->departure_to,
                        'departure_from' => $departure->departure_from,
                        'departure_to' => $departure->departure_to,
                    ];
                })->toArray();
            }

            // Keep original slug (removed /activities/ prefix)
            // $product->slug = '/activities/' . $product->slug;

            // Keep earliest_departure field for API response
            $product->earliest_departure = $product->earliest_departure;

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
