<?php

namespace Modules\Product\App\Service\User;

use Illuminate\Database\Eloquent\Builder;
use Modules\Product\App\Models\Product;

class SearchProductService
{

    public function search(string $query)
    {
        return Product::query()
            ->where('status', 'published')
            ->where(function (Builder $builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%")
                    ->orWhere('type', 'like', "%{$query}%")
                    ->orWhere('short_description', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhereHas('tags', function (Builder $tagQuery) use ($query) {
                        $tagQuery->where('name', 'like', "%{$query}%")
                            ->orWhere('slug', 'like', "%{$query}%")
                            ->orWhere('description', 'like', "%{$query}%");
                    });
            })
            ->with('tags')
            ->get()
            ->map(function ($product) {
                return [
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
