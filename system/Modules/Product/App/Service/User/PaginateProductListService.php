<?php

namespace Modules\Product\App\Service\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\App\Models\Product;

class PaginateProductListService
{
    public function getPaginatedProducts(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        try {
            $query = $this->getBaseQuery();

            $this->applyFilters($query, $filters);
            $products = $query->paginate($perPage);
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
                'products.tagline',
                'products.slug',
                'products.type',
                'products.display_order',
                'products.latitude',
                'products.longitude',
                'products.location',
                'products.average_rating',
                'products.total_rating',
            ])
            ->where('products.status', 'published')
            ->where('products.is_occupied', false)
            ->with([
                'tags' => function ($query) {
                    $query->select('tags.id', 'tags.name', 'tags.description', 'display_order', 'zoom_level', 'tags.slug', 'tags.latitude', 'tags.longitude');
                },
                'prices' => function ($query) {
                    $query->select('product_id', 'number_of_people', 'original_price_usd', 'discounted_price_usd')
                        ->orderBy('number_of_people', 'asc');
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

    private function applyFilters(Builder $query, array $filters): void
    {
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['tags']) && is_array($filters['tags']) && !empty($filters['tags'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->where(function ($subQuery) use ($filters) {
                    foreach ($filters['tags'] as $tag) {
                        if (is_numeric($tag)) {
                            $subQuery->orWhere('tags.id', $tag);
                        } else {
                            $subQuery->orWhere('tags.slug', $tag);
                        }
                    }
                });
            });
        }

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['bounds'])) {
            $query->whereBetween('latitude', [$filters['bounds']['south'], $filters['bounds']['north']])
                ->whereBetween('longitude', [$filters['bounds']['west'], $filters['bounds']['east']]);
        }

        if (isset($filters['price'])) {
            if (isset($filters['price']['min'])) {
                $query->whereHas('prices', function ($q) use ($filters) {
                    $q->where('discounted_price_usd', '>=', $filters['price']['min']);
                });
            }
            if (isset($filters['price']['max'])) {
                $query->whereHas('prices', function ($q) use ($filters) {
                    $q->where('discounted_price_usd', '<=', $filters['price']['max']);
                });
            }
        }
    }

    private function transformProducts(LengthAwarePaginator $products): LengthAwarePaginator
    {
        $products->getCollection()->transform(function ($product) {
            $product->featuredImage = $this->getMediaFiles($product, 'featuredImage');
            $product->featuredImages = $this->getMediaFiles($product, 'featuredImages', true);
            $product->tags = $product->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ];
            })->values()->toArray();
            $product->prices = $product->prices->map(function ($price) {
                return [
                    'number_of_people' => $price->number_of_people,
                    'original_price_usd' => $price->original_price_usd,
                    'discounted_price_usd' => $price->discounted_price_usd,
                ];
            })->values()->toArray();
            return $product;
        });
        return $products;
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
