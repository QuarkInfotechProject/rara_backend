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

            $products = $query->limit(7)->get();

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
                'products.short_description',
            ])
            ->where('products.status', 'published')
            ->where('products.is_occupied', false)
//            ->where('products.display_homepage', true)
            ->orderByRaw('CAST(products.display_order AS SIGNED) ASC');
    }

    private function transformProducts($products)
    {
        return $products->map(function ($product) {
            $product->featuredImage = $this->getMediaFiles($product, 'featuredImage');
            $product->slug = '/activities/' . $product->slug; // 👈 prepend /treks/
            return (object) [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'type' => $product->type,
                'short_description' => $product->short_description,
                'featuredImage' => $product->featuredImage,
            ];
        });
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
