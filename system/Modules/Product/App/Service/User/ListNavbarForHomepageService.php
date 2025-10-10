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
                'categories' => $this->transformProductsByCategory($products)
            ];

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private function getBaseQuery()
    {
        return Product::query()
            ->where('status', 'published')
            ->with(['category.categoryDetail']); // only load category relation
    }

    private function transformProductsByCategory($products)
    {
        $navbar = [];

        $products->each(function ($product) use (&$navbar) {
            if ($product->category && $product->category->categoryDetail) {
                $category = $product->category->categoryDetail;
                $categoryId = str()->slug($category->name);

                if (!isset($navbar[$categoryId])) {
                    $navbar[$categoryId] = [
                        'id' => $category->id,
                        'name' => $category->name,
                        'products' => []
                    ];
                }

                $navbar[$categoryId]['products'][] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'featured_image' => $this->getMediaFiles($product, 'featuredImage'),
                ];
            }
        });

        return array_values($navbar); // indexed array for JSON
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
