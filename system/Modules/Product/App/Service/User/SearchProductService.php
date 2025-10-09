<?php

namespace Modules\Product\App\Service\User;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\App\Models\Product;
use Illuminate\Support\Collection;

class SearchProductService
{
    public function search(string $query): Collection
    {
        $query = trim($query);

        if (empty($query)) {
            return collect([]);
        }

        // Escape special characters for LIKE
        $escapedQuery = addcslashes($query, '%_\\');

        // Patterns for "starts with" and "contains"
        $startsWithTerm = $escapedQuery . '%';
        $containsTerm = '%' . $escapedQuery . '%';

        // Product search (by name or type)
        $products = Product::query()
            ->where('status', 'published')
            ->where(function (Builder $builder) use ($startsWithTerm, $containsTerm) {
                $builder->where('name', 'like', $startsWithTerm)
                    ->orWhere('type', 'like', $startsWithTerm)
                    ->orWhere('name', 'like', $containsTerm)
                    ->orWhere('type', 'like', $containsTerm);
            })
            ->with('tags')
            ->get();

        // Tag-based search (without description or slug contains)
        $productsWithTags = Product::query()
            ->where('status', 'published')
            ->whereHas('tags', function (Builder $tagQuery) use ($startsWithTerm, $containsTerm) {
                $tagQuery->where('name', 'like', $startsWithTerm)
//                    ->orWhere('slug', 'like', $startsWithTerm)
                    ->orWhere('name', 'like', $containsTerm);
            })
            ->with('tags')
            ->get();

        // Merge results and remove duplicates
        $allProducts = $products->merge($productsWithTags)->unique('id');

        // Sort: items starting with query come first
        $sorted = $allProducts->sortByDesc(function ($product) use ($query) {
            return stripos($product->name, $query) === 0 || stripos($product->type, $query) === 0;
        });

        // Return simplified structure
        return $sorted->values()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'type' => $product->type,
                'tagline' => $product->tagline,
                'featured_image' => $this->getMediaFiles($product, 'featuredImage'),
            ];
        });
    }

    private function getMediaFiles($product, $type, $multiple = false)
    {
        $files = $product->filterFiles($type)->get();

        if ($multiple) {
            return $files->map(fn($file) => $file->path . '/' . $file->temp_filename)->toArray();
        }

        $file = $files->first();
        return $file ? $file->path . '/' . $file->temp_filename : '';
    }
}
