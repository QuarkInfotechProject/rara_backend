<?php

namespace Modules\Product\App\Service\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\App\Models\Product;

class ListExploreForHomepageService
{
    public function getExploreProducts()
    {
        try {
            $query = $this->getBaseQuery();

            // Get 7 random products from all types
            $products = $query->inRandomOrder()->limit(7)->get();

            return $this->transformProducts($products);

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
            ])
            ->where('products.status', 'published')
            ->where('products.is_occupied', false)
            ->where('products.display_homepage', true);
    }

    private function transformProducts($products)
    {
        return $products->map(function ($product) {
            $product->featuredImage = $this->getMediaFiles($product, 'featuredImage');
            $product->featuredImages = $this->getMediaFiles($product, 'featuredImages', true);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'type' => $product->type,
                'featuredImage' => $product->featuredImage,
                'featuredImages' => $product->featuredImages,
            ];
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
