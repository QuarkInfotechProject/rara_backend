<?php

namespace Modules\Product\App\Service\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\App\Models\Product;


class ListHeroProductForHomepageService
{
    public function getHeroProducts()
    {
        try {
            $types = ['trek', 'tour', 'activities', 'safari'];
            $products = collect();

            foreach ($types as $type) {
                $product = $this->getBaseQuery()
                    ->where('type', $type)
                    ->latest('created_at') // get the latest
                    ->with(['prices' => function($query) {
                        $query->select('product_id', 'number_of_people', 'original_price_usd', 'discounted_price_usd')
                            ->orderBy('number_of_people', 'asc');
                    }])
                    ->first();

                if ($product) {
                    $products->push($this->transformProduct($product));
                }
            }

            return $products;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private function getBaseQuery(): Builder
    {
        return Product::query()
            ->select([
                'products.id',
                'products.name',
                'products.slug',
                'products.type',
//                'products.created_at',
            ])
            ->where('products.status', 'published');
//            ->where('products.is_occupied', false)
//            ->orderByRaw('CAST(products.display_order AS SIGNED) ASC');
    }

    private function transformProduct($product)
    {
        $product->featuredImage = $this->getMediaFiles($product, 'featuredImage');
        $product->slug = '/' . $product->type . '/' . $product->slug;

        // Transform prices
        $prices = $product->prices->map(function ($price) {
            return [
//                'number_of_people' => $price->number_of_people,
                'original_price_usd' => $price->original_price_usd,
//                'discounted_price_usd' => $price->discounted_price_usd,
            ];
        })->values()->toArray();

        return (object) [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'type' => $product->type,
//            'created_at' => $product->created_at->format('Y-m-d H:i:s'),
            'featuredImage' => $product->featuredImage,
            'prices' => $prices,
        ];
    }

    private function getMediaFiles($product, $type, $multiple = false)
    {
        $baseImageFiles = $product->filterFiles($type)->get();

        if ($multiple) {
            return $baseImageFiles->map(fn($file) => $file->path . '/' . $file->temp_filename)->toArray();
        }

        return $baseImageFiles->map(fn($file) => $file->path . '/' . $file->temp_filename)->first() ?? '';
    }
}
