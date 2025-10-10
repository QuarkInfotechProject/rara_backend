<?php

namespace Modules\Product\App\Service\User;

use Modules\Product\App\Models\Product;

class ListNavbarForHomepageService
{
    public function getNavbar()
    {
        try {
            $products = $this->getBaseQuery()->get();

            return [
                'data' => $this->transformProductsByTypeAndCategory($products)
            ];

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private function getBaseQuery()
    {
        return Product::query()
            ->where('status', 'published')
            ->with(['category.categoryDetail']); // only eager-load category
    }

    private function transformProductsByTypeAndCategory($products)
    {
        $navbar = [];

        $products->each(function ($product) use (&$navbar) {
            $type = $product->type ?? 'others';

            if (!isset($navbar[$type])) {
                $navbar[$type] = [];
            }

            if ($product->category && $product->category->categoryDetail) {
                $category = $product->category->categoryDetail;
                $categoryId = str()->slug($category->name);

                // Initialize category if not exists
                if (!isset($navbar[$type][$categoryId])) {
                    $navbar[$type][$categoryId] = [
                        'id' => $category->id,
                        'name' => $category->name,
                        'products' => []
                    ];
                }

                $navbar[$type][$categoryId]['products'][] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'featured_image' => $this->getMediaFiles($product, 'featuredImage'),
                ];
            }
        });

        // Convert categories to indexed arrays for each type
        foreach ($navbar as $type => &$categories) {
            $categories = array_values($categories);
        }

        return $navbar;
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
